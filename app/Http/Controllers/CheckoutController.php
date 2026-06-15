<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderFacade;
use App\Models\Order;
use App\Models\Voucher;
use App\Factories\Voucher\VoucherFactory;
use Carbon\Carbon;
use App\Singletons\SystemConfig;

class CheckoutController extends Controller
{
    protected $orderFacade;

    // Inject Facade Service vào thông qua hàm khởi tạo
    public function __construct(OrderFacade $orderFacade)
    {
        $this->orderFacade = $orderFacade;
    }

    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $config = SystemConfig::getInstance();
        $shippingFee = $config->get('shipping_fee', 20000);

        // Lấy danh sách địa chỉ cũ từ bảng user_addresses
        $savedAddresses = [];
        if (auth()->check()) {
            $savedAddresses = \Illuminate\Support\Facades\DB::table('user_addresses')
                                ->where('user_id', auth()->id())
                                ->orderBy('created_at', 'desc')
                                ->get();
        }

        return view('checkout.index', compact('cart', 'subtotal', 'shippingFee', 'savedAddresses'));
    }

    public function process(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        // 1. Gọi Facade tạo Order cơ bản trước
        $order = $this->orderFacade->placeOrder($request->all(), $cart);

        // 2. Tính toán lại dòng tiền từ giỏ hàng thực tế
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $config = SystemConfig::getInstance();
        $delivery_fee = (float) $config->get('shipping_fee', 20000);
        $discount_applied = 0;

        // 3. TÍCH HỢP FACTORY METHOD VÀO BƯỚC ĐẶT HÀNG CHÍNH THỨC
        if ($request->filled('voucher_code')) {
            $voucher = \App\Models\Voucher::where('code', strtoupper($request->voucher_code))->first();
            
            if ($voucher && $subtotal >= $voucher->min_order_value) {
                $isExpired = $voucher->expires_at && \Carbon\Carbon::parse($voucher->expires_at)->isPast();
                
                // Đảm bảo voucher còn hạn và còn số lượng lượt dùng
                if (!$isExpired && $voucher->quantity > 0) {
                    try {
                        // Gọi "Nhà máy" ra để tính lại số tiền giảm chuẩn xác 100%
                        $processor = \App\Factories\Voucher\VoucherFactory::make($voucher->type);
                        $discount_applied = $processor->calculateDiscount($subtotal, $voucher);
                        
                        // Trừ đi 1 lượt sử dụng trong Database
                        $voucher->decrement('quantity');
                    } catch (\Exception $e) {
                        $discount_applied = 0;
                    }
                }
            }
        }

        // Ép kiểu float để đảm bảo biến này KHÔNG BAO GIỜ bị null (khắc phục dứt điểm lỗi SQL)
        $discount_applied = (float) $discount_applied;

        // 4. Tính toán tổng tiền cuối cùng (Dùng max để tránh tổng tiền bị âm)
        $total_amount = max(0, $subtotal + $delivery_fee - $discount_applied);

        // 5. Cập nhật đè lại các thông số tài chính chuẩn xác vào Database
        $order->update([
            'subtotal' => $subtotal,
            'delivery_fee' => $delivery_fee,
            'discount_applied' => $discount_applied,
            'total_amount' => $total_amount
        ]);

        // Xóa giỏ hàng và dữ liệu mã giảm giá lưu tạm
        session()->forget(['cart', 'applied_voucher']);

        // Trả về view thành công (khuyên dùng redirect để tránh bị gửi lại Form khi F5)
        return view('checkout.success', compact('order'));
    }

    public function success(Request $request)
    {
        // Lấy dữ liệu thật từ MySQL lên hiển thị ra hóa đơn
        $order = Order::with('items')->findOrFail($request->id);

        return view('checkout.success', compact('order'));
    }

    public function checkVoucher(Request $request)
    {
        $code = $request->input('code');
        $subtotal = (float) $request->input('subtotal', 0);

        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập mã giảm giá!'
            ]);
        }

        $voucher = \App\Models\Voucher::where('code', $code)->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại hoặc đã hết hạn!'
            ]);
        }

        if (isset($voucher->quantity) && $voucher->quantity <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá này đã hết lượt sử dụng!'
            ]);
        }

        try {
            // Khởi tạo bộ xử lý từ Factory Method
            $processor = \App\Factories\Voucher\VoucherFactory::make($voucher->type);
            $discountAmount = $processor->calculateDiscount($subtotal, $voucher);
            
            $finalTotal = max(0, $subtotal - $discountAmount);

            // Lưu thông tin đồng bộ vào Session phục vụ cho bước Đặt hàng cuối cùng
            session()->put('applied_voucher', [
                'code' => $voucher->code,
                'discount_amount' => $discountAmount
            ]);

            return response()->json([
                'success' => true,
                'discount_amount' => $discountAmount,
                'final_total' => $finalTotal,
                'message' => 'Áp dụng mã giảm giá thành công!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi hệ thống khi xử lý mã giảm giá: ' . $e->getMessage()
            ]);
        }
    }

    public function updateAddress(Request $request)
    {
        $user = auth()->user();
        if ($user && $request->has('address')) {
            $destinationAddress = $request->input('address');

            // --- ÁP DỤNG ADAPTER PATTERN NGAY KHI VỪA CHỌN MAP XONG ---
            // Gọi Adapter đóng vai trò "thông dịch viên" lấy số Km
            $mapAdapter = new \App\Adapters\Map\OpenStreetMapAdapter();
            
            // Tính từ tọa độ quán (VD: ĐH Thủy Lợi) đến địa chỉ khách vừa chọn
            $distanceKm = $mapAdapter->getDistanceInKm('Đại học Thủy Lợi, Đống Đa, Hà Nội', $destinationAddress);

            // Thêm bản ghi mới vào bảng lịch sử địa chỉ
            \Illuminate\Support\Facades\DB::table('user_addresses')->insert([
                'user_id'    => $user->id,
                'address'    => $destinationAddress,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Trả về cho Frontend cả địa chỉ VÀ số Km đã tính toán qua API
            return response()->json([
                'success' => true, 
                'address' => $destinationAddress,
                'distance_km' => $distanceKm 
            ]);
        }
        
        return response()->json(['success' => false]);
    }
}
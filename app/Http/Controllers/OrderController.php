<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // Hiển thị danh sách lịch sử đơn hàng
    public function history(Request $request)
    {
        $statusFilter = $request->query('status', 'all');
        
        // CHỈ LẤY ĐƠN HÀNG CỦA USER ĐANG ĐĂNG NHẬP
        $query = Order::where('user_id', auth()->id())
                      ->with('items')
                      ->orderBy('created_at', 'desc');

        // Lọc theo trạng thái (Luồng sự kiện thay thế A1)
        if ($statusFilter === 'delivering') {
            $query->where('status', 'delivering');
        } elseif ($statusFilter === 'completed') {
            $query->where('status', 'completed');
        } elseif ($statusFilter === 'pending') {
            $query->where('status', 'pending');
        }

        $orders = $query->get();

        return view('order.history', compact('orders', 'statusFilter'));
    }

    // Xử lý nút "Đặt lại" (Luồng sự kiện thay thế A3)
    // Xử lý đặt lại đơn hàng cũ với đầy đủ thông tin tùy chọn
    public function reorder($id)
    {
        // Eager load sâu từ đơn hàng cũ sang món ăn, nhà hàng và danh sách các topping đã chọn
        $order = Order::with(['items.food.restaurant', 'items.toppings'])->findOrFail($id);
        $cart = session()->get('cart', []);

        foreach ($order->items as $item) {
            // Tạo mã cart_id duy nhất cho từng thực thể giỏ hàng
            $cartId = md5($item->food_name . time() . rand(1, 100)); 
            
            // Gom toàn bộ danh sách tên topping cũ thành một chuỗi ngăn cách bằng dấu phẩy
            $toppingsString = $item->toppings->pluck('topping_name')->implode(', ');

            $cart[$cartId] = [
                'id'         => $item->food_id,
                'name'       => $item->food_name,
                'price'      => $item->price,
                'quantity'   => $item->quantity,
                'image'      => $item->food ? $item->food->image : null, 
                // Nạp đúng tên nhà hàng sở hữu món ăn
                'restaurant' => $item->food && $item->food->restaurant ? $item->food->restaurant->name : 'Quán ăn đối tác',
                // Đồng bộ chính xác tên Size khớp với key $item['size'] ở View giỏ hàng
                'size'       => $item->size_name, 
                // Đồng bộ chuỗi Topping khớp với key $item['toppings'] ở View giỏ hàng
                'toppings'   => $toppingsString, 
                'note'       => ''
            ];
        }

        // Lưu đè lại mảng dữ liệu hoàn chỉnh vào Session giỏ hàng
        session()->put('cart', $cart);
        
        return redirect()->route('cart.index')->with('success', 'Đã sao chép các món và tùy chọn cũ vào giỏ!');
    }

    // Xử lý khi người dùng xác nhận đã nhận hàng
    public function confirmReceived($id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);
        
        if ($order->status !== 'completed') {
            // Lệnh update này sẽ đánh thức Observer tự động chạy ngầm!
            $order->update(['status' => 'completed']);
        }
        
        return redirect()->back()->with('success', 'Xác nhận thành công! Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi.');
    }
    public function exportPdf($orderId)
    {
        // 1. Tìm đơn hàng
        $order = Order::findOrFail($orderId);

        // 2. Sử dụng Builder để tạo file PDF
        $pdf = (new \App\Builders\InvoiceBuilder($order))
                    ->addCustomerInfo()
                    ->addFoodItems()
                    ->applyTaxes()
                    ->buildPdf();

        // 3. Trả về file cho trình duyệt tải xuống
        return $pdf->download('Hoa_Don_' . $order->order_code . '.pdf');
    }
}
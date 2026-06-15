<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderFacade
{
    public function placeOrder(array $customerData, array $cart)
    {
        // Sử dụng Database Transaction để đảm bảo tính toàn vẹn dữ liệu (Lỗi một bước là hủy toàn bộ)
        return DB::transaction(function () use ($customerData, $cart) {
            
            // 1. Tính toán số tiền từ giỏ hàng thật
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }
            
            $shippingFee = 20000; // Phí giao cố định 20k
            
            $discount = (isset($customerData['voucher_code']) && $customerData['voucher_code'] === 'AJC18S36') ? 17000 : 0;
            $total = $subtotal + $shippingFee - $discount;

            // 2. Tạo bản ghi đơn hàng 

            $order = Order::create([
                'order_code'       => 'MN' . strtoupper(Str::random(6)),
                'user_id'          => auth()->id(), 
                'customer_name'    => $customerData['name'],
                'phone'            => $customerData['phone'],
                'delivery_address' => $customerData['address'],     
                'delivery_time'    => $customerData['time_type'] ?? null,   
                'payment_method'   => 'Tiền mặt (COD)',           
                'subtotal'         => $subtotal,
                'delivery_fee'     => $shippingFee,
                'discount_applied' => $discount,
                'total_amount'     => $total,
                'status'           => 'pending', 
                'note'             => $customerData['order_note'] ?? null,
            ]);

            // 3. Tạo các bản ghi chi tiết đơn hàng
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'  => $order->id,
                    'food_id'   => $item['id'],
                    'food_name' => $item['name'],
                    'quantity'  => $item['quantity'],
                    'price'     => $item['price'],
                ]);
            }

            // 4. Xóa sạch giỏ hàng sau khi đặt thành công
            session()->forget('cart');

            return $order;
        });
    }
}
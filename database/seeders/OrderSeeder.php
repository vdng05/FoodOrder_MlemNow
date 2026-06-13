<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('phone', '0937243758')->first();

        // Đơn 1: Đang giao
        $order1 = Order::create([
            'order_code' => '#MN30041975',
            'user_id' => $user->id ?? 1,
            'customer_name' => 'Nguyễn Văn A',
            'phone' => '0937243758',
            'delivery_address' => '26 Đông Tác, Kim Liên, Đống Đa, Hà Nội',
            'payment_method' => 'COD',
            'subtotal' => 220000,
            'delivery_fee' => 20000,
            'discount_applied' => 17000,
            'total_amount' => 223000,
            'status' => 'delivering',
            'created_at' => '2026-05-30 14:18:00',
        ]);

        OrderItem::insert([
            ['order_id' => $order1->id, 'food_name' => 'Pizza hải sản', 'size_name' => 'Size L', 'quantity' => 1, 'price' => 120000],
            ['order_id' => $order1->id, 'food_name' => 'Mì ý sốt cay', 'size_name' => 'Size nhỏ', 'quantity' => 2, 'price' => 50000],
        ]);

        // Đơn 2: Đã hoàn tất
        $order2 = Order::create([
            'order_code' => '#MN23124313',
            'user_id' => $user->id ?? 1,
            'customer_name' => 'Nguyễn Văn A',
            'phone' => '0937243758',
            'delivery_address' => '26 Đông Tác, Kim Liên, Đống Đa, Hà Nội',
            'payment_method' => 'COD',
            'subtotal' => 239000,
            'delivery_fee' => 20000,
            'discount_applied' => 0,
            'total_amount' => 259000,
            'status' => 'completed',
            'created_at' => '2026-05-30 14:00:00',
        ]);

        OrderItem::insert([
            ['order_id' => $order2->id, 'food_name' => 'Burger bò', 'size_name' => 'Size nhỏ', 'quantity' => 2, 'price' => 80000],
            ['order_id' => $order2->id, 'food_name' => 'Burger Phô Mai', 'size_name' => 'Size M', 'quantity' => 1, 'price' => 79000],
        ]);
    }
}
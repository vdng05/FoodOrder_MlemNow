<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Voucher;

class VoucherSeeder extends Seeder
{
    public function run()
    {
        Voucher::create([
            'code' => 'MLEM17K',
            'discount_amount' => 17000,
            'min_order_value' => 200000,
            'is_active' => true,
        ]);
    }
}
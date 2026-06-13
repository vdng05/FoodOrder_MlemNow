<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Food;
use App\Models\Size;
use App\Models\Topping;

class SizeAndToppingSeeder extends Seeder
{
    public function run()
    {
        $pizza = Food::where('name', 'Pizza Hải Sản')->first();
        $com = Food::where('name', 'Cơm Sườn Nướng')->first();

        if ($pizza) {
            Size::insert([
                ['food_id' => $pizza->id, 'name' => 'Size S', 'extra_price' => 0],
                ['food_id' => $pizza->id, 'name' => 'Size M', 'extra_price' => 30000],
                ['food_id' => $pizza->id, 'name' => 'Size L', 'extra_price' => 60000],
            ]);
            Topping::insert([
                ['food_id' => $pizza->id, 'name' => 'Thêm tôm', 'extra_price' => 20000],
                ['food_id' => $pizza->id, 'name' => 'Thêm phô mai', 'extra_price' => 15000],
            ]);
        }

        if ($com) {
            Size::insert([
                ['food_id' => $com->id, 'name' => 'Size S', 'extra_price' => 0],
                ['food_id' => $com->id, 'name' => 'Size M', 'extra_price' => 15000],
                ['food_id' => $com->id, 'name' => 'Size L', 'extra_price' => 30000],
            ]);
            Topping::insert([
                ['food_id' => $com->id, 'name' => 'Thêm sườn', 'extra_price' => 20000],
                ['food_id' => $com->id, 'name' => 'Thêm cơm', 'extra_price' => 15000],
            ]);
        }
    }
}
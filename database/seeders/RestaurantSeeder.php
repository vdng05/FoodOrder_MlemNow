<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Restaurant;

class RestaurantSeeder extends Seeder
{
    public function run()
    {
        $restaurants = [
            ['name' => 'Pizza House', 'rating' => 4.8, 'distance' => 1.2],
            ['name' => 'Burger Town', 'rating' => 4.7, 'distance' => 0.9],
            ['name' => 'Chicken King', 'rating' => 4.9, 'distance' => 2.0],
            ['name' => 'Korean Food', 'rating' => 4.6, 'distance' => 1.5],
            ['name' => 'Cơm Ngon 24h', 'rating' => 4.9, 'distance' => 2.0],
            ['name' => 'Cơm Tấm Sài Gòn', 'rating' => 4.9, 'distance' => 3.0],
            ['name' => 'Beef Rice House', 'rating' => 4.9, 'distance' => 2.0],
            ['name' => 'Mlem Coffee House', 'rating' => 4.9, 'distance' => 1.7],
        ];
        foreach ($restaurants as $res) {
            Restaurant::create($res);
        }
    }
}
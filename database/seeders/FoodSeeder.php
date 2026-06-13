<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Food;
use App\Models\Category;
use App\Models\Restaurant;

class FoodSeeder extends Seeder
{
    public function run()
    {
        // Lấy ID chuẩn bị map
        $catPizza = Category::where('name', 'Pizza')->first()->id;
        $catBurger = Category::where('name', 'Burger')->first()->id;
        $catGa = Category::where('name', 'Gà rán')->first()->id;
        $catCom = Category::where('name', 'Cơm')->first()->id;
        $catDoUong = Category::where('name', 'Đồ uống')->first()->id;

        $resPizza = Restaurant::where('name', 'Pizza House')->first()->id;
        $resBurger = Restaurant::where('name', 'Burger Town')->first()->id;
        $resChicken = Restaurant::where('name', 'Chicken King')->first()->id;
        $resComTam = Restaurant::where('name', 'Cơm Tấm Sài Gòn')->first()->id;
        $resComNgon = Restaurant::where('name', 'Cơm Ngon 24h')->first()->id;
        $resCoffee = Restaurant::where('name', 'Mlem Coffee House')->first()->id;

        Food::insert([
            ['category_id' => $catPizza, 'restaurant_id' => $resPizza, 'name' => 'Pizza Hải Sản', 'description' => 'Pizza hải sản phô mai', 'base_price' => 120000],
            ['category_id' => $catPizza, 'restaurant_id' => $resPizza, 'name' => 'Pizza Pepperoni', 'description' => 'Pizza xúc xích pepperoni', 'base_price' => 95000],
            ['category_id' => $catBurger, 'restaurant_id' => $resBurger, 'name' => 'Burger Bò', 'description' => 'Burger bò nướng', 'base_price' => 80000],
            ['category_id' => $catBurger, 'restaurant_id' => $resBurger, 'name' => 'Burger Phô Mai', 'description' => 'Cách bạn 1.2km', 'base_price' => 89000],
            ['category_id' => $catGa, 'restaurant_id' => $resChicken, 'name' => 'Gà Rán Cay', 'description' => 'Gà rán giòn cay Hàn Quốc', 'base_price' => 95000],
            ['category_id' => $catCom, 'restaurant_id' => $resComNgon, 'name' => 'Cơm Gà', 'description' => 'Cách bạn 750m', 'base_price' => 65000],
            ['category_id' => $catCom, 'restaurant_id' => $resComTam, 'name' => 'Cơm Sườn Nướng', 'description' => 'Cơm sườn nướng thơm ngon', 'base_price' => 80000],
            ['category_id' => $catDoUong, 'restaurant_id' => $resCoffee, 'name' => 'Cà Phê Đen Đá', 'description' => 'Cà phê đen nguyên chất', 'base_price' => 25000],
        ]);
    }
}
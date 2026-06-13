<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Pizza'], ['name' => 'Burger'], ['name' => 'Gà rán'],
            ['name' => 'Đồ uống'], ['name' => 'Mì cay'], ['name' => 'Cơm']
        ];
        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
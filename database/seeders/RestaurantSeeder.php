<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;

class RestaurantSeeder extends Seeder
{
    public function run()
    {
        $restaurants = [
            [
                'name' => 'Pizza House', 
                'rating' => 4.8, 
                'distance' => 1.2, 
                'specialties' => 'Pizza Hải Sản • Mì Ý • Salad',
                'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1200' 
            ],
            [
                'name' => 'Burger Town', 
                'rating' => 4.7, 
                'distance' => 0.9, 
                'specialties' => 'Hamburger Bò • Khoai tây chiên • Gà rán',
                'image' => 'https://images.unsplash.com/photo-1552566626-52f8b828add9?q=80&w=1200' 
            ],
            [
                'name' => 'Chicken King', 
                'rating' => 4.9, 
                'distance' => 2.0, 
                'specialties' => 'Gà rán giòn rụm • Gà quay • Burger gà',
                'image' => 'https://images.unsplash.com/photo-1466978913421-dad2ebd01d17?q=80&w=1200' 
            ],
            [
                'name' => 'Korean Food', 
                'rating' => 4.6, 
                'distance' => 1.5, 
                'specialties' => 'Mì cay 7 cấp độ • Tokbokki • Kimbap',
                'image' => 'https://images.unsplash.com/photo-1514933651103-005eec06c04b?q=80&w=1200' 
            ],
            [
                'name' => 'Cơm Ngon 24h', 
                'rating' => 4.9, 
                'distance' => 2.0, 
                'specialties' => 'Cơm thố • Cơm rang dưa bò • Canh chua',
                'image' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?q=80&w=1200' 
            ],
            [
                'name' => 'Cơm Tấm Sài Gòn', 
                'rating' => 4.8, 
                'distance' => 2.1, 
                'specialties' => 'Sườn bì chả • Trứng ốp la • Canh khổ qua',
                'image' => 'https://images.unsplash.com/photo-1554679665-f5537f187268?q=80&w=1200' 
            ],
            [
                'name' => 'Beef Rice House', 
                'rating' => 4.9, 
                'distance' => 2.0, 
                'specialties' => 'Cơm bò lúc lắc • Bò bít tết • Salad bò',
                'image' => 'https://images.unsplash.com/photo-1544148103-0773bf10d330?q=80&w=1200' 
            ],
            [
                'name' => 'Mlem Coffee House', 
                'rating' => 4.9, 
                'distance' => 1.7, 
                'specialties' => 'Cà phê ủ lạnh • Trà đào cam sả • Bánh ngọt',
                'image' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=1200' 
            ],
            [
                'name' => 'Healthy Meal', 
                'rating' => 4.8, 
                'distance' => 1.3, 
                'specialties' => 'Salad ức gà • Cơm gạo lứt • Nước ép detox',
                'image' => 'https://images.unsplash.com/photo-1493770348161-369560ae357d?q=80&w=1200' 
            ],
            [
                'name' => 'Seafood House', 
                'rating' => 4.7, 
                'distance' => 2.5, 
                'specialties' => 'Tôm hùm xướng bơ tỏi • Cua biển • Mực hấp',
                'image' => 'https://images.unsplash.com/photo-1534080564583-6be75777b70a?q=80&w=1200' 
            ],
        ];

        foreach ($restaurants as $res) {
            Restaurant::updateOrCreate(
                ['name' => $res['name']], 
                [
                    'rating' => $res['rating'],
                    'distance' => $res['distance'],
                    'image' => $res['image'],
                    'specialties' => $res['specialties'] // Cập nhật dữ liệu mới vào DB
                ]
            );
        }
    }
}
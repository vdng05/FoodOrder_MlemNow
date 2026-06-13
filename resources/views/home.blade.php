@extends('layouts.app')

@section('content')
    <section class="banner">
        <div>
            <h1>Đặt món ăn nhanh chóng</h1>
            <p>Giao diện hiện đại - thao tác đơn giản</p>
        </div>
    </section>

    <div class="container">
        <section class="categories">
            @foreach($categories as $category)
                <button>{{ $category->name }}</button>
            @endforeach
        </section>

        <div class="section-title">🍜 Danh mục món ăn</div>
        <div class="slider-wrapper">
            <button class="slider-btn left" onclick="scrollSlider('categorySlider',-300)">‹</button>
            <div class="slider" id="categorySlider">
                @foreach($categories as $category)
                    <div class="food-card">
                        <img src="{{ $category->image ?? 'https://images.unsplash.com/photo-1513104890138-7c749659a591?q=80&w=1200&auto=format&fit=crop' }}">
                        <div class="food-content">
                            <h3>{{ $category->name }}</h3>
                            <button>Xem món</button>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="slider-btn right" onclick="scrollSlider('categorySlider',300)">›</button>
        </div>

        <div class="section-title" id="featured">🔥 Món ăn nổi bật</div>
        <div class="slider-wrapper">
            <button class="slider-btn left" onclick="scrollSlider('featuredSlider',-300)">‹</button>
            <div class="slider" id="featuredSlider">
                @foreach($featuredFoods as $food)
                    <div class="food-card">
                        <div class="favorite-btn">❤</div>
                        <img src="{{ $food->image ?? 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?q=80&w=1200&auto=format&fit=crop' }}">
                        <div class="food-content">
                            <h3>{{ $food->name }}</h3>
                            <p>{{ $food->description }}</p>
                            <div class="price">{{ number_format($food->base_price, 0, ',', '.') }}đ</div>
                            <a href="{{ route('food.detail', $food->id) }}" class="buy-now-btn" style="text-align: center; display: block; text-decoration: none; margin-top: 10px;">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="slider-btn right" onclick="scrollSlider('featuredSlider',300)">›</button>
        </div>

        <div class="section-title">📍 Món ăn gần bạn</div>
        <div class="slider-wrapper">
            <div class="slider" id="nearbySlider">
                @foreach($nearbyFoods as $food)
                    <div class="food-card">
                        <img src="{{ $food->image ?? 'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=1200&auto=format&fit=crop' }}">
                        <div class="food-content">
                            <h3>{{ $food->name }}</h3>
                            <p>Cách bạn {{ $food->restaurant->distance ?? 1.5 }}km</p>
                            <div class="price">{{ number_format($food->base_price, 0, ',', '.') }}đ</div>
                            <button>Xem chi tiết</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="section-title">🏪 Quán ăn gần bạn</div>
        <div class="slider-wrapper">
            <button class="slider-btn left" onclick="scrollSlider('restaurantSlider',-300)">‹</button>
            <div class="slider" id="restaurantSlider">
                @foreach($restaurants as $restaurant)
                    <div class="food-card">
                        <img src="{{ $restaurant->image ?? 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=1200&auto=format&fit=crop' }}">
                        <div class="food-content">
                            <h3>{{ $restaurant->name }}</h3>
                            <p>⭐ {{ $restaurant->rating }} • {{ $restaurant->distance }}km</p>
                            <button>Xem quán</button>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="slider-btn right" onclick="scrollSlider('restaurantSlider',300)">›</button>
        </div>
    </div>
@endsection
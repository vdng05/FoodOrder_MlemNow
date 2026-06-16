@extends('layouts.app')

@section('content')
    <section class="banner">
        <div>
            <h1>Đặt món ăn nhanh chóng</h1>
            <p>Giao diện hiện đại - thao tác đơn giản</p>
        </div>
    </section>

    <div class="container">
        <section class="categories" style="margin-bottom: 40px;">
            @foreach($categories as $category)
                <button onclick="window.location.href='{{ route('search', ['category_id' => $category->id]) }}'">
                    {{ $category->name }}
                </button>
            @endforeach
        </section>

        <div class="section-header">
            <div class="section-title" id="featured">🔥 Món ăn nổi bật</div>
            <a href="{{ route('search', ['sort' => 'rating']) }}" class="view-all">Xem tất cả &gt;</a>
        </div>
        <div class="slider-wrapper">
            <button class="slider-btn left" onclick="scrollSlider('featuredSlider',-300)">‹</button>
            <div class="slider" id="featuredSlider">
                @foreach($featuredFoods as $food)
                    <div class="food-card clickable-card" onclick="window.location.href='{{ route('food.detail', $food->id) }}'">
                        <img src="{{ $food->image ?? asset('images/default-food.jpg') }}" alt="{{ $food->name }}">
                        <div class="food-content">
                            <h3>{{ $food->name }}</h3>
                            <p class="food-desc">{{ $food->description }}</p>
                            
                            <div class="food-meta">
                                <span class="rating">
                                    <i class="fas fa-star" style="color: #ffc107;"></i> {{ '⭐ ' . ($food->restaurant->rating ?? '5.0') }}
                                </span>
                                <span class="time">
                                    <i class="far fa-clock"></i> {{ '🕒 ' . ($food->prep_time ?? 15) }} phút
                                </span>
                                <span class="distance">
                                    {{ $food->restaurant->distance ?? '2' }}km
                                </span>
                            </div>

                            <div class="food-price-row">
                                <div class="price">{{ number_format($food->base_price, 0, ',', '.') }}đ</div>
                                <div class="sold-count">
                                    {{ $food->sold_count >= 1000 ? round($food->sold_count / 1000, 1) . 'k+' : ($food->sold_count ?? 0) }} đã bán
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="slider-btn right" onclick="scrollSlider('featuredSlider',300)">›</button>
        </div>

        <div class="section-header">
            <div class="section-title">📍 Món ăn gần bạn</div>
            <a href="{{ route('search', ['sort' => 'distance']) }}" class="view-all">Xem tất cả &gt;</a>
        </div>
        <div class="slider-wrapper">
            <button class="slider-btn left" onclick="scrollSlider('nearbySlider',-300)">‹</button>
            <div class="slider" id="nearbySlider">
                @foreach($nearbyFoods as $food)
                    <div class="food-card clickable-card" onclick="window.location.href='{{ route('food.detail', $food->id) }}'">
                        <img src="{{ $food->image ?? asset('images/default-food.jpg') }}" alt="{{ $food->name }}">
                        <div class="food-content">
                            <h3>{{ $food->name }}</h3>
                            <p class="food-desc">{{ $food->description }}</p>
                            
                            <div class="food-meta">
                                <span class="rating">
                                    <i class="fas fa-star" style="color: #ffc107;"></i> {{ '⭐ ' .  ($food->restaurant->rating ?? '5.0') }}
                                </span>
                                <span class="time">
                                    <i class="far fa-clock"></i> {{ '🕒 ' . ($food->prep_time ?? 15) }} phút
                                </span>
                                <span class="distance">
                                    {{ $food->restaurant->distance ?? '2' }}km
                                </span>
                            </div>

                            <div class="food-price-row">
                                <div class="price">{{ number_format($food->base_price, 0, ',', '.') }}đ</div>
                                <div class="sold-count">
                                    {{ $food->sold_count >= 1000 ? round($food->sold_count / 1000, 1) . 'k+' : ($food->sold_count ?? 0) }} đã bán
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="slider-btn right" onclick="scrollSlider('nearbySlider',300)">›</button>
        </div>

        <div class="section-header">
            <div class="section-title">🏪 Quán ăn gần bạn</div>
            <a href="{{ route('restaurants.index') }}" class="view-all">Xem tất cả &gt;</a>
        </div>
        <div class="slider-wrapper">
            <button class="slider-btn left" onclick="scrollSlider('restaurantSlider',-300)">‹</button>
            <div class="slider" id="restaurantSlider">
                @foreach($restaurants as $restaurant)
                    <div class="food-card clickable-card" onclick="window.location.href='{{ route('search', ['restaurant_id' => $restaurant->id]) }}'" style="display: flex; flex-direction: column;">
                        
                        <!-- Ảnh quán ăn -->
                        <img src="{{ $restaurant->image ?? asset('images/default-restaurant.jpg') }}" alt="{{ $restaurant->name }}" style="width: 100%; height: 150px; object-fit: cover;">
                        
                        <!-- Khối nội dung -->
                        <div class="food-content" style="padding: 15px; display: flex; flex-direction: column; flex: 1; text-align: left;">
                            
                            <!-- Tên quán -->
                            <h3 style="margin: 0 0 8px 0; font-size: 18px; color: #222; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                {{ $restaurant->name }}
                            </h3>
                            
                            <!-- Đánh giá và Khoảng cách -->
                            <div style="display: flex; align-items: baseline; color: #777; font-size: 14px; margin-bottom: 12px;">
                                <i class="fas fa-star" style="color: #ffc107; margin-right: 5px;"></i>
                                <span style="color: #555; font-weight: 500;">{{ '⭐ '. $restaurant->rating ?? '4.8' }}</span>
                                <span style="margin: 0 8px; font-size: 12px;">•</span>
                                <span>{{ $restaurant->distance ?? '1.2' }}km</span>
                            </div>

                            <!-- Mô tả món ăn nổi bật (Tags) -->
                            <div style="font-size: 13.5px; color: #888; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: auto;">
                                <!-- Chú ý: Bạn cần thay trường 'specialties' bằng trường thực tế trong Database của bạn -->
                                {{ $restaurant->specialties ?? 'Cơm tấm • Cơm gà • Canh' }}
                            </div>
                            
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="slider-btn right" onclick="scrollSlider('restaurantSlider',300)">›</button>
        </div>
    </div>
@endsection
<script>
    // ==========================================
        // LOGIC ẨN/HIỆN NÚT SLIDER THÔNG MINH
        // ==========================================
        
        function updateSliderButtons(slider) {
            // Tìm 2 nút trái/phải nằm cùng khối với slider hiện tại
            const leftBtn = slider.parentElement.querySelector('.slider-btn.left');
            const rightBtn = slider.parentElement.querySelector('.slider-btn.right');
            
            if (!leftBtn || !rightBtn) return;

            // Ẩn nút trái nếu đang ở vị trí đầu tiên
            if (slider.scrollLeft <= 0) {
                leftBtn.classList.add('hide');
            } else {
                leftBtn.classList.remove('hide');
            }

            // Ẩn nút phải nếu đã cuộn đến vị trí cuối cùng
            // (Cộng thêm 2 pixel để bù trừ sai số làm tròn của một số trình duyệt)
            if (Math.ceil(slider.scrollLeft + slider.clientWidth) >= slider.scrollWidth - 2) {
                rightBtn.classList.add('hide');
            } else {
                rightBtn.classList.remove('hide');
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Lấy tất cả các dải trượt trên trang
            const sliders = document.querySelectorAll('.slider');
            
            sliders.forEach(slider => {
                // 1. Kiểm tra trạng thái ngay khi vừa load trang (ẩn nút trái)
                updateSliderButtons(slider);
                
                // 2. Lắng nghe mỗi khi người dùng vuốt/cuộn
                slider.addEventListener('scroll', function() {
                    updateSliderButtons(slider);
                });
            });

            // 3. Tính toán lại khi người dùng xoay ngang điện thoại hoặc co giãn màn hình
            window.addEventListener('resize', function() {
                sliders.forEach(slider => updateSliderButtons(slider));
            });
        });

        // (Code cuộn slider hiện tại của bạn, tôi viết lại để tối ưu hơn)
        function scrollSlider(id, amount) {
            const slider = document.getElementById(id);
            if (slider) {
                slider.scrollBy({
                    left: amount,
                    behavior: "smooth"
                });
            }
        }
</script>
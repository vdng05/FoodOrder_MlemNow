@extends('layouts.app')

@section('content')
<div class="container">
    <section class="detail-section" style="display: flex;"> <div class="detail-image">
            <img src="{{ $food->image ?? asset('images/default-food.jpg') }}" alt="{{ $food->name }}">
        </div>

        <div class="detail-info">
            <h2>{{ $food->name }}</h2>
            
            <div class="restaurant-name">
                🍴 {{ $food->restaurant->name ?? 'Quán ăn đối tác' }}
            </div>
            
            <div class="restaurant-rating">
                ⭐ {{ $food->restaurant->rating ?? '4.5' }} • {{ $food->restaurant->distance ?? '1.2' }}km
            </div>

            <div class="detail-price">
                @if($food->sale_price)
                    <span class="old-price">{{ number_format($food->base_price, 0, ',', '.') }}đ</span>
                    <span id="foodPrice" data-base="{{ $food->sale_price }}">{{ number_format($food->sale_price, 0, ',', '.') }}đ</span>
                @else
                    <span id="foodPrice" data-base="{{ $food->base_price }}">{{ number_format($food->base_price, 0, ',', '.') }}đ</span>
                @endif
            </div>

            <p class="food-description">
                {{ $food->description }}
            </p>

            <form action="#" method="POST" id="addToCartForm">
                @csrf
                <input type="hidden" name="food_id" value="{{ $food->id }}">

                @if($food->sizes->count() > 0)
                <div class="option-group">
                    <h3>Chọn size</h3>
                    @foreach($food->sizes as $index => $size)
                        <label class="option-item">
                            <div>
                                <input type="radio" name="size_id" value="{{ $size->id }}" data-price="{{ $size->extra_price }}" onchange="calculateTotal()" {{ $index == 0 ? 'checked' : '' }}>
                                {{ $size->name }}
                            </div>
                            <span class="option-price">+{{ number_format($size->extra_price, 0, ',', '.') }}đ</span>
                        </label>
                    @endforeach
                </div>
                @endif

                @if($food->toppings->count() > 0)
                <div class="option-group">
                    <h3>Topping</h3>
                    @foreach($food->toppings as $topping)
                        <label class="option-item">
                            <div>
                                <input type="checkbox" name="toppings[]" value="{{ $topping->id }}" data-price="{{ $topping->extra_price }}" onchange="calculateTotal()">
                                {{ $topping->name }}
                            </div>
                            <span class="option-price">+{{ number_format($topping->extra_price, 0, ',', '.') }}đ</span>
                        </label>
                    @endforeach
                </div>
                @endif

                <div class="option-group">
                    <h3>Số lượng</h3>
                    <div class="cart-quantity" style="margin-bottom: 20px;">
                        <button type="button" onclick="updateQty(-1)">-</button>
                        <input type="number" id="qtyInput" name="quantity" value="1" min="1" readonly style="width: 50px; text-align: center; border: none; font-size: 20px; font-weight: bold; background: transparent;">
                        <button type="button" onclick="updateQty(1)">+</button>
                    </div>

                    <h3>Ghi chú</h3>
                    <textarea name="note" rows="3" style="width: 100%; border: 1px solid #ccc; border-radius: 10px; padding: 10px;" placeholder="Ví dụ: Lấy nhiều tương ớt..."></textarea>
                </div>

                <div class="detail-buttons">
                    <button type="button" class="add-cart-btn" onclick="showToast()">
                        🛒 Thêm vào giỏ
                    </button>
                    <button type="submit" class="buy-now-btn">
                        Mua ngay
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    function updateQty(change) {
        let input = document.getElementById('qtyInput');
        let current = parseInt(input.value);
        let newValue = current + change;
        if(newValue >= 1) {
            input.value = newValue;
            calculateTotal();
        }
    }

    function calculateTotal() {
        // Lấy giá gốc
        let basePrice = parseInt(document.getElementById('foodPrice').getAttribute('data-base'));
        
        // Cộng tiền Size
        let sizeInput = document.querySelector('input[name="size_id"]:checked');
        let sizePrice = sizeInput ? parseInt(sizeInput.getAttribute('data-price')) : 0;
        
        // Cộng tiền Topping
        let toppingPrice = 0;
        document.querySelectorAll('input[name="toppings[]"]:checked').forEach(function(cb) {
            toppingPrice += parseInt(cb.getAttribute('data-price'));
        });
        
        // Nhân số lượng
        let qty = parseInt(document.getElementById('qtyInput').value);
        let total = (basePrice + sizePrice + toppingPrice) * qty;
        
        // Cập nhật lên UI
        document.getElementById('foodPrice').innerText = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
    }

    // Tính giá lần đầu khi vừa vào trang
    window.onload = function() {
        calculateTotal();
    };
</script>
@endpush
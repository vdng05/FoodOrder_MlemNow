@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@section('content')
    <style>
        /* Hiệu ứng xuất hiện cho Modal */
        @keyframes popIn {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Hiệu ứng hover cho các nút trong Modal */
        .btn-modal-cancel:hover {
            background-color: #f0f0f0 !important;
        }

        .btn-modal-delete:hover {
            background-color: #e64a19 !important;
        }
        /* Hiệu ứng hover cho nút Bắt đầu đặt món */
        .btn-empty-cart:hover {
            background-color: #e64a19 !important; /* Cam đậm */
        }
        /* CSS cho Toast Cảnh báo */
        .warning-toast-container {
            position: fixed;
            top: 30px;
            right: 30px;
            z-index: 999999;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .warning-toast {
            background-color: #ffffff;
            border-left: 5px solid #ff9800; /* Màu vàng cam cảnh báo */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 6px;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 320px;
            position: relative;
            overflow: hidden;
            animation: toastSlideIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        }

        .warning-toast.closing {
            animation: toastSlideOut 0.4s forwards;
        }

        .warning-toast .toast-icon {
            font-size: 24px;
            color: #ff9800;
        }

        .warning-toast .toast-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .warning-toast .toast-title {
            font-weight: bold;
            color: #333;
            font-size: 15px;
            margin-bottom: 4px;
        }

        .warning-toast .toast-message {
            color: #666;
            font-size: 13px;
        }

        .warning-toast .toast-close {
            color: #aaa;
            cursor: pointer;
            font-size: 18px;
            transition: 0.2s;
        }

        .warning-toast .toast-close:hover {
            color: #333;
        }

        .warning-toast .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background-color: #ff9800;
            width: 100%;
            animation: toastProgress 3s linear forwards;
        }

        @keyframes toastSlideIn {
            from { transform: translateX(120%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes toastSlideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(120%); opacity: 0; }
        }

        @keyframes toastProgress {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>
    <div class="warning-toast-container" id="warningToastContainer"></div>

    <div class="cart-page" style="max-width: 1000px; margin: 40px auto; padding: 20px;">
        <div class="cart-header"
            style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px;">
            <div class="cart-title"
                style="display: inline-flex; align-items: center; gap: 12px; font-size: 28px; font-weight: bold; color: #000;">
                <i class="fas fa-shopping-cart" style="font-size: 32px; color: #000000;"></i>
                <span>Giỏ hàng</span>
            </div>
            <div class="cart-count"
                style="background: #ffceb3; color: #ff5722; padding: 5px 15px; border-radius: 20px; font-weight: bold;">
                {{ count($cart ?? []) }} món
            </div>
        </div>

        @if(empty($cart))
            <div class="empty-cart-container" 
                style="text-align: center; padding: 70px 20px; background: #fff; border-radius: 12px; border: 1px solid #eee;">
                
                <img src="{{ asset('images/empty-cart.png') }}" alt="Giỏ hàng trống" 
                    style="width: 180px; margin-bottom: 20px; object-fit: contain;">
                
                <h3 style="font-size: 18px; color: #333; margin-bottom: 8px; font-weight: bold;">
                    Giỏ hàng trống
                </h3>
                
                <p style="font-size: 15px; color: #777; margin-bottom: 25px; margin-top: 0;">
                    Bạn chưa thêm món ăn nào vào giỏ hàng!
                </p>
                
                <a href="{{ route('home') }}" class="btn-empty-cart"
                    style="display: inline-block; background: #ff5722; color: #fff; padding: 12px 35px; border-radius: 6px; font-weight: bold; text-decoration: none; font-size: 15px; transition: 0.3s;">
                    Khám phá món ăn
                </a>
                
            </div>
        @else
            <div class="cart-items">
                @php $total = 0; @endphp
                @foreach($cart as $cartId => $item)
                    @php $total += $item['price'] * $item['quantity']; @endphp

                    <div class="cart-item"
                        style="display: flex; background: #fafafa; border-radius: 12px; padding: 20px; margin-bottom: 20px; border: 1px solid #eee;">
    
                        <div style="margin-right: 20px; display: flex; align-items: center;">
                            <input type="checkbox" class="item-checkbox" checked
                                data-price="{{ $item['price'] * $item['quantity'] }}"
                                style="transform: scale(1.5); accent-color: #ff5722; cursor: pointer;" onchange="updateCartTotal()">
                        </div>

                        <img src="{{ $item['image'] ?? asset('images/default-food.jpg') }}" alt="Food Image"
                            style="width: 130px; height: 130px; border-radius: 10px; object-fit: cover; margin-right: 20px;">

                        <div class="item-info" style="flex: 1;">
                            <div class="item-name" style="font-size: 22px; font-weight: bold; color: #000; margin-bottom: 5px;">
                                {{ $item['base_name'] ?? $item['name'] }}
                            </div>

                            <div class="item-restaurant" style="font-size: 14px; color: #555; margin-bottom: 5px;">
                                🏪 {{ $item['restaurant'] ?? 'Quán ăn đối tác' }}
                            </div>

                            <div class="item-variant" style="font-size: 14px; color: #333; margin-bottom: 10px;">
                                {{ $item['size'] ?? '' }}
                                @if(!empty($item['toppings']))
                                    &nbsp;•&nbsp; {{ $item['toppings'] }}
                                @endif

                                @if(!empty($item['note']))
                                    <br><i style="color: #888;">Ghi chú: {{ $item['note'] }}</i>
                                @endif
                            </div>

                            <div class="item-unit-price" style="font-size: 22px; font-weight: bold; color: #ff5722;">
                                {{ number_format($item['price'], 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="item-actions" style="display: flex; flex-direction: column; align-items: flex-end; gap: 15px;">
                            <form action="{{ route('cart.update') }}" method="POST"
                                style="display: flex; align-items: center; background: #fff; border-radius: 6px; overflow: hidden; height: 35px; box-shadow: 0 0 0 1px #eee;">
                                @csrf
                                <input type="hidden" name="cart_id" value="{{ $cartId }}">
                                
                                <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}"
                                    style="width: 35px; height: 100%; border: none; font-size: 18px; font-weight: bold; transition: 0.3s; margin: 0; padding: 0; display: flex; align-items: center; justify-content: center;
                                        background: {{ $item['quantity'] <= 1 ? '#d6d6d6' : '#ff5722' }}; 
                                        color: {{ $item['quantity'] <= 1 ? '#888888' : 'white' }}; 
                                        cursor: {{ $item['quantity'] <= 1 ? 'not-allowed' : 'pointer' }};"
                                    {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                    -
                                </button>
                                
                                <span style="width: 40px; height: 100%; display: flex; align-items: center; justify-content: center; color: #000; background: #fff; font-weight: bold; margin: 0; padding: 0; border-top: 1px solid #eee; border-bottom: 1px solid #eee;">
                                    {{ $item['quantity'] }}
                                </span>
                                
                                <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}"
                                    style="width: 35px; height: 100%; background: #ff5722; border: none; color: white; font-size: 18px; cursor: pointer; font-weight: bold; margin: 0; padding: 0; display: flex; align-items: center; justify-content: center;">
                                    +
                                </button>
                            </form>

                            <div class="item-total-price" style="font-size: 22px; font-weight: bold; color: #000;">
                                {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ
                            </div>

                            <button type="button"
                                onclick="confirmDelete('{{ $cartId }}', '{{ $item['base_name'] ?? $item['name'] }}')"
                                style="background: transparent; color: #ff5722; border: none; font-size: 16px; cursor: pointer; font-weight: bold;">
                                Xóa
                            </button>

                            <form id="delete-form-{{ $cartId }}" action="{{ route('cart.remove') }}" method="POST"
                                style="display: none;">
                                @csrf
                                <input type="hidden" name="cart_id" value="{{ $cartId }}">
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="cart-summary"
                style="margin-top: 30px; padding: 25px; border-radius: 12px; background: #fff; border: 1px solid #eee; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                
                <div class="summary-row"
                    style="display: flex; justify-content: space-between; margin-bottom: 15px; padding-right: 230px; font-size: 16px; color: #555;">
                    <span>Tạm tính:</span>
                    <span id="subtotal" style="min-width: 100px; text-align: right;">0đ</span>
                </div>
                
                <div class="summary-row"
                    style="display: flex; justify-content: space-between; margin-bottom: 20px; padding-right: 230px; font-size: 16px; color: #555;">
                    <span>Phí giao hàng:</span>
                    <span id="deliveryFee" style="min-width: 100px; text-align: right;">20.000đ</span>
                </div>

                <hr style="border: none; border-top: 1px dashed #ddd; margin-bottom: 20px;">

                <div class="summary-row total" style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 20px; font-weight: bold; color: #333;">Tổng cộng:</span>
                    <div style="display: flex; align-items: center; gap: 30px;">
                        <span id="total" style="font-size: 22px; font-weight: bold; color: #ff5722; min-width: 100px; text-align: right;">0đ</span>
                        
                        <button type="button" onclick="proceedToCheckout()"
                            style="width: 200px; background: #ff5722; color: white; padding: 14px 0; border: none; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.3s; text-align: center;">
                            Tiến hành đặt hàng
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div id="customConfirmModal"
    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:10000; align-items:center; justify-content:center;">
    
    <div style="background:#fff; border-radius:12px; width:450px; overflow:hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.2); animation: popIn 0.3s ease;">
        
        <div style="background: #ff5722; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 18px; font-weight: bold; color: white;">Xác nhận xóa</h3>
            <i class="fas fa-times" onclick="closeConfirmModal()" style="color: white; font-size: 20px; cursor: pointer;"></i>
        </div>
        
        <div style="padding: 30px 20px 40px 20px; display: flex; align-items: flex-start; gap: 15px;">
            <i class="fas fa-exclamation-circle" style="color: #f39c12; font-size: 30px; margin-top: 2px;"></i>
            <p style="font-size: 16px; color: #333; margin: 0; line-height: 1.5; padding-top: 5px;" id="confirmMessage"></p>
        </div>

        <div style="padding: 0 20px 20px 20px; display: flex; justify-content: flex-end; gap: 15px;">
            <button type="button" id="btnConfirmDelete" class="btn-modal-delete"
                style="background:#ff5722; color:white; border:none; padding:10px 25px; border-radius:6px; font-weight:bold; cursor:pointer; font-size: 15px; transition: 0.3s;">
                Đồng ý
            </button>
            
            <button type="button" onclick="closeConfirmModal()" class="btn-modal-cancel"
                style="background:#fff; color:#ff5722; border:1px solid #ff5722; padding:10px 25px; border-radius:6px; font-weight:bold; cursor:pointer; font-size: 15px; transition: 0.3s;">
                Hủy
            </button>
        </div>
        
    </div>
    </div>
    <div id="warningCenterModal"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:10000; align-items:center; justify-content:center;">
        
        <div style="background:#fff; border-radius:14px; width:320px; text-align:center; padding: 30px 20px 25px 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); animation: popIn 0.3s ease;">
            
            <i class="fas fa-exclamation-circle" style="color: #ff9800; font-size: 55px; margin-bottom: 15px;"></i>
            
            <h3 style="margin: 0 0 10px 0; font-size: 18px; font-weight: bold; color: #222;">Bạn chưa chọn món</h3>
            
            <p style="font-size: 14.5px; color: #666; margin: 0 0 25px 0; line-height: 1.5; padding: 0 10px;">
                Vui lòng chọn món ăn bạn muốn thanh toán!
            </p>
            
            <button onclick="closeWarningCenterModal()" class="btn-modal-delete"
                style="background:#ff5722; color:white; border:none; padding:12px 0; width: 100%; border-radius:8px; font-weight:bold; cursor:pointer; font-size: 15px; transition: 0.3s;">
                Đã hiểu
            </button>
            
        </div>
    </div>
</div>

    <script>
        // ----- LOGIC CUSTOM CONFIRM MODAL -----
        let currentDeleteCartId = null;

        // Thay thế hàm confirm() mặc định bằng Custom Modal
        function confirmDelete(cartId, foodName) {
            currentDeleteCartId = cartId; // Lưu ID món ăn cần xóa
            
            // Cập nhật câu hỏi xác nhận cho sát với thiết kế
            document.getElementById('confirmMessage').innerHTML = "Bạn có chắc muốn xóa món ăn \"" + foodName + "\" ?";
            
            document.getElementById('customConfirmModal').style.display = 'flex';
        }

        // Hàm đóng Modal Hủy
        function closeConfirmModal() {
            document.getElementById('customConfirmModal').style.display = 'none';
            currentDeleteCartId = null; // Reset lại ID
        }

        // Sự kiện khi bấm nút Xóa trong Modal
        document.getElementById('btnConfirmDelete').addEventListener('click', function () {
            if (currentDeleteCartId) {
                document.getElementById('delete-form-' + currentDeleteCartId).submit();
            }
        });

        // ----- LOGIC TÍNH TOÁN GIỎ HÀNG -----
        function updateCartTotal() {
            let subtotal = 0;
            let deliveryFee = 20000; // Mặc định phí ship 20k

            let checkboxes = document.querySelectorAll('.item-checkbox');
            let hasChecked = false;

            checkboxes.forEach(function (checkbox) {
                if (checkbox.checked) {
                    subtotal += parseInt(checkbox.getAttribute('data-price'));
                    hasChecked = true;
                }
            });

            // Nếu không có món nào được chọn, phí ship bằng 0
            if (!hasChecked) {
                deliveryFee = 0;
            }

            let total = subtotal + deliveryFee;

            document.getElementById('subtotal').innerText = new Intl.NumberFormat('vi-VN').format(subtotal) + 'đ';
            document.getElementById('deliveryFee').innerText = new Intl.NumberFormat('vi-VN').format(deliveryFee) + 'đ';
            document.getElementById('total').innerText = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
        }

        // Tự động tính tiền khi load trang xong
        window.onload = function () {
            if (document.querySelectorAll('.item-checkbox').length > 0) {
                updateCartTotal();
            }
        };
        // ----- LOGIC CẢNH BÁO CHƯA CHỌN MÓN -----
        let warningTimeout;

        function proceedToCheckout() {
            let checkboxes = document.querySelectorAll('.item-checkbox');
            let hasChecked = false;

            // Kiểm tra xem có checkbox nào đang được tick không
            checkboxes.forEach(function (checkbox) {
                if (checkbox.checked) {
                    hasChecked = true;
                }
            });

            if (hasChecked) {
                // Có chọn món -> Đi tới trang thanh toán
                window.location.href = "{{ route('checkout.index') }}";
            } else {
                // Không chọn món -> Hiện Pop-up giữa màn hình
                showWarningCenterModal();
            }
        }

        function showWarningCenterModal() {
            let modal = document.getElementById('warningCenterModal');
            modal.style.display = 'flex';
            
            // Xóa bộ đếm thời gian cũ nếu người dùng nhấn nút liên tục
            if (warningTimeout) {
                clearTimeout(warningTimeout);
            }
            
            // Tự động đóng Pop-up sau 3 giây (3000ms)
            warningTimeout = setTimeout(function() {
                closeWarningCenterModal();
            }, 3000);
        }

        function closeWarningCenterModal() {
            let modal = document.getElementById('warningCenterModal');
            if (modal) {
                modal.style.display = 'none';
            }
        }
    </script>
@endsection
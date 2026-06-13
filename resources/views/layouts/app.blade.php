<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MlemNow</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <header>
        <div class="header-container">
            <div class="logo">
                <a href="{{ route('home') }}" style="display: flex; align-items: center; text-decoration: none;">
                    <img src="{{ asset('images/logo.png') }}" alt="MlemNow Logo" style="height: 65px; width: auto; object-fit: contain; margin-right: 15px;">
                    <span style="color: white; font-size: 50px; font-weight: bold; line-height: 65px;">MlemNow</span>
                </a>
            </div>
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Tìm món ăn hoặc quán ăn...">
                <button onclick="searchFood()"> Tìm kiếm </button>
            </div>
            <nav>
                <a href="{{ route('home') }}"> Trang chủ </a>
                <a href="#nearbySection"> Gần bạn </a>
                <a href="#cartSection"> Giỏ hàng </a>
                <a href="#loginSection" class="login-btn"> Đăng nhập </a>
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <div class="toast" id="toast">
        ✅ Đã thêm món ăn vào giỏ hàng
    </div>

    <div class="modal-overlay" id="successModal" style="display: none;">
        <div class="bill-content">
            <div class="bill-header">
                <h2 style="display: flex; justify-content: center; align-items: center; gap: 10px; margin-bottom: 5px;">
                    <img src="{{ asset('images/logo.png') }}" alt="MlemNow Logo" style="height: 40px; width: auto; object-fit: contain;">
                    MlemNow
                </h2>
                <p>HÓA ĐƠN ĐẶT HÀNG</p>
            </div>
            <div class="bill-info">
                <p><strong>Mã đơn:</strong> <span style="color:#ff5722">#FD2026VN</span></p>
                <p><strong>Thời gian:</strong> <span id="billDate">...</span></p>
                <p><strong>Khách hàng:</strong> Khách lẻ</p>
                <p><strong>Thanh toán:</strong> Tiền mặt (COD)</p>
            </div>
            <table class="bill-table">
                <thead>
                    <tr>
                        <th>Món ăn</th>
                        <th>SL</th>
                        <th>Giá</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Pizza Hải Sản (Size L)</td>
                        <td>1</td>
                        <td>120K</td>
                    </tr>
                </tbody>
            </table>
            <div class="bill-summary">
                <div class="bill-row bill-total">
                    <span>TỔNG CỘNG:</span> 
                    <span>120.000đ</span>
                </div>
            </div>
            <div class="bill-footer">
                <p>🙏 Cảm ơn quý khách đã tin tưởng!</p>
                <p>Nhà hàng đang chuẩn bị món và tài xế sẽ giao đến trong thời gian sớm nhất.</p>
            </div>
            <button class="confirm-order-btn" onclick="goHome()">Đóng & Về trang chủ</button>
        </div>
    </div>

    <footer>
        MlemNow Modern UI - HCI Project
    </footer>

    <script>
        function scrollSlider(id, amount) {
            document.getElementById(id).scrollBy({
                left: amount,
                behavior: "smooth"
            });
        }

        function openDetail(name, price, image) {
            let detailSection = document.getElementById("detailSection");
            if(detailSection) {
                detailSection.style.display = "flex";
                document.getElementById("foodName").innerText = name;
                document.getElementById("foodPrice").innerText = price;
                if(image) document.getElementById("detailImage").src = image;
                detailSection.scrollIntoView({ behavior: "smooth" });
            }
        }

        function showToast() {
            let toast = document.getElementById("toast");
            toast.classList.add("show");
            setTimeout(() => { toast.classList.remove("show"); }, 2000);
        }

        function goHome() {
            document.getElementById("successModal").style.display = "none";
            let checkoutSection = document.getElementById("checkoutSection");
            if(checkoutSection) checkoutSection.style.display = "none";
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
    
    @stack('scripts')

</body>
</html>
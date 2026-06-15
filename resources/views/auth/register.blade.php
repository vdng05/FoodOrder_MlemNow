@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

@section('content')
<div class="container" style="max-width: 500px; margin-top: 50px; background: white; padding: 30px; border-radius: 15px;">
    <div class="auth-logo-wrapper" style="margin-bottom: 15px; text-align: center;">
        <img src="{{ asset('images/logo.png') }}" alt="MlemNow Logo" style="height: 80px; width: auto; object-fit: contain; display: inline-block;">
    </div>
    <h2 style="text-align: center; color: #ff5722; margin-bottom: 20px;">Đăng Ký Tài Khoản</h2>
    
    @if($errors->any())
        <div style="color: red; margin-bottom: 15px;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST" class="checkout-form">
        @csrf
        <div class="form-group">
            <label>Họ và tên</label>
            <input type="text" name="name" required placeholder="Nhập họ tên">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required placeholder="Nhập email">
        </div>
        <div class="form-group">
            <label>Số điện thoại</label>
            <input type="text" name="phone" required placeholder="Nhập số điện thoại">
        </div>
        
        <div class="form-group">
            <label>Mật khẩu</label>
            <div style="position: relative;">
                <input type="password" name="password" required placeholder="Nhập mật khẩu" style="padding-right: 40px; width: 100%; box-sizing: border-box;">
                <i class="fas fa-eye toggle-password" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #888;"></i>
            </div>
        </div>
        
        <div class="form-group">
            <label>Xác nhận mật khẩu</label>
            <div style="position: relative;">
                <input type="password" name="password_confirmation" required placeholder="Nhập lại mật khẩu" style="padding-right: 40px; width: 100%; box-sizing: border-box;">
                <i class="fas fa-eye toggle-password" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #888;"></i>
            </div>
        </div>
        
        <button type="submit" class="confirm-order-btn">Đăng ký</button>
    </form>
    
    <p style="text-align: center; margin-top: 15px;">
        Đã có tài khoản? <a href="{{ route('login') }}" style="color: #ff5722; font-weight: bold; text-decoration: none;">Đăng nhập</a>
    </p>
</div>

<script>
    // JS Xử lý ẩn hiện mật khẩu dùng chung cho cả 2 ô
    document.querySelectorAll('.toggle-password').forEach(item => {
        item.addEventListener('click', function () {
            const input = this.closest('div').querySelector('input');
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });
    });
</script>
@endsection
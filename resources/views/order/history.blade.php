@extends('layouts.app')

@section('content')
<div class="history-container">
    <div class="history-header">
        <h2>📦 Lịch sử đơn hàng</h2>
    </div>

    <div class="history-filters">
        <a href="{{ route('orders.history', ['status' => 'all']) }}" class="filter-btn {{ $statusFilter == 'all' ? 'active' : '' }}">Tất cả</a>
        <a href="{{ route('orders.history', ['status' => 'pending']) }}" class="filter-btn {{ $statusFilter == 'pending' ? 'active' : '' }}">Chờ xác nhận</a>
        <a href="{{ route('orders.history', ['status' => 'delivering']) }}" class="filter-btn {{ $statusFilter == 'delivering' ? 'active' : '' }}">Đang giao</a>
        <a href="{{ route('orders.history', ['status' => 'completed']) }}" class="filter-btn {{ $statusFilter == 'completed' ? 'active' : '' }}">Đã giao</a>
    </div>

    @if($orders->isEmpty())
        <div class="empty-history">
            <p>Bạn chưa có đơn hàng nào.</p>
            <a href="{{ route('home') }}" class="btn-primary">Đặt món ngay</a>
        </div>
    @else
        <div class="order-list">
            @foreach($orders as $order)
                @php
                    // Tạo chuỗi tóm tắt món ăn
                    $summaryItems = [];
                    foreach($order->items as $item) {
                        $summaryItems[] = $item->quantity . 'x ' . $item->food_name;
                    }
                    $summaryText = implode(', ', $summaryItems);

                    // TRUY XUẤT THÔNG TIN QUÁN ĂN THẬT TỪ DATABASE
                    $restaurantName = 'MlemNow - Quán ăn';
                    $restaurantImage = 'https://ui-avatars.com/api/?name=Mlem+Now&background=ffceb3&color=ff5722&size=120'; // Ảnh mặc định an toàn ko bị lỗi

                    $firstItem = $order->items->first();
                    if ($firstItem && $firstItem->food && $firstItem->food->restaurant) {
                        $restaurantName = $firstItem->food->restaurant->name;
                        $restaurantImage = $firstItem->food->restaurant->image ?? $restaurantImage;
                    }
                @endphp

                <div class="order-card">
                    <img src="{{ $restaurantImage }}" alt="{{ $restaurantName }}" class="order-img" onerror="this.src='https://ui-avatars.com/api/?name=Error&background=ddd&color=333&size=120'">
                    
                    <div class="order-info">
                        <h3>{{ $restaurantName }}</h3>
                        <p class="order-meta">
                            Mã đơn: #{{ $order->order_code }} | {{ $order->created_at->format('d/m/Y, H:i A') }}
                        </p>
                        
                        <div class="order-summary-box">
                            {{ $summaryText }}
                        </div>

                        <div class="order-status">
                            @if($order->status == 'delivering')
                                <span class="badge warning">Đang giao</span>
                            @elseif($order->status == 'completed')
                                <span class="badge success">Đã hoàn tất</span>
                            @elseif($order->status == 'pending')
                                <span class="badge default">Chờ xác nhận</span>
                            @else
                                <span class="badge default">{{ $order->getStateLabel() ?? 'Chờ xác nhận' }}</span>
                            @endif
                            
                            @if($order->status == 'delivering' || $order->status == 'pending')
                                    <form action="{{ route('order.confirm', $order->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn-primary" style="background-color: #ff5722; border-color: #ff5722; color: white;">Đã nhận được hàng</button>
                                    </form>
                            @endif
                        </div>
                    </div>

                    <div class="order-actions">
                        <div class="order-total">{{ number_format($order->total_amount, 0, ',', '.') }}đ</div>
                        <div class="action-buttons">
                            <a href="{{ route('checkout.success', ['id' => $order->id]) }}" class="btn-outline">Xem hóa đơn</a>
                            
                            <form action="{{ route('orders.reorder', $order->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-primary">Đặt lại</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
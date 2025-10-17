@extends('layouts.app')

@section('title', 'Chi Tiết Đơn Hàng #' . $order->id)

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tài Khoản</h5>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-user me-2"></i>Thông Tin Cá Nhân
                        </a>
                        <a href="{{ route('profile.orders') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-shopping-bag me-2"></i>Đơn Hàng
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="list-group-item list-group-item-action border-0 bg-transparent text-start w-100">
                                <i class="fas fa-sign-out-alt me-2"></i>Đăng Xuất
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Tài Khoản</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('profile.orders') }}">Đơn Hàng</a></li>
                    <li class="breadcrumb-item active">#{{ $order->id }}</li>
                </ol>
            </nav>

            <!-- Order Header -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Đơn Hàng #{{ $order->id }}</h5>
                            <small class="text-muted">Đặt lúc {{ $order->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <div class="col-auto">
                            @switch($order->status)
                                @case('pending')
                                    <span class="badge bg-warning text-dark fs-6">Chờ xử lý</span>
                                    @break
                                @case('processing')
                                    <span class="badge bg-info fs-6">Đang xử lý</span>
                                    @break
                                @case('shipped')
                                    <span class="badge bg-primary fs-6">Đã gửi</span>
                                    @break
                                @case('delivered')
                                    <span class="badge bg-success fs-6">Đã giao</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger fs-6">Đã hủy</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary fs-6">{{ ucfirst($order->status) }}</span>
                            @endswitch
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Thông Tin Giao Hàng</h6>
                            <address class="mb-0">
                                <strong>{{ $order->shipping_name }}</strong><br>
                                {{ $order->shipping_address }}<br>
                                {{ $order->shipping_phone }}<br>
                                @if($order->shipping_email)
                                    <abbr title="Email">Email:</abbr> {{ $order->shipping_email }}
                                @endif
                            </address>
                        </div>
                        <div class="col-md-6">
                            <h6>Thông Tin Thanh Toán</h6>
                            <dl class="row mb-0">
                                <dt class="col-sm-5">Phương thức:</dt>
                                <dd class="col-sm-7">
                                    @switch($order->payment_method)
                                        @case('cod')
                                            <span class="badge bg-warning text-dark">Thanh toán khi nhận hàng</span>
                                            @break
                                        @case('bank_transfer')
                                            <span class="badge bg-info">Chuyển khoản ngân hàng</span>
                                            @break
                                        @case('credit_card')
                                            <span class="badge bg-success">Thẻ tín dụng</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($order->payment_method) }}</span>
                                    @endswitch
                                </dd>
                                <dt class="col-sm-5">Trạng thái:</dt>
                                <dd class="col-sm-7">
                                    @switch($order->payment_status)
                                        @case('pending')
                                            <span class="badge bg-warning text-dark">Chờ thanh toán</span>
                                            @break
                                        @case('paid')
                                            <span class="badge bg-success">Đã thanh toán</span>
                                            @break
                                        @case('failed')
                                            <span class="badge bg-danger">Thanh toán thất bại</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                                    @endswitch
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Sản Phẩm Đã Đặt</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản Phẩm</th>
                                    <th>Đơn Giá</th>
                                    <th>Số Lượng</th>
                                    <th>Thành Tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product)
                                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" 
                                                     class="me-3" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                    @if($item->product->sku)
                                                        <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                                    @endif
                                                </div>
                                            @else
                                                <div>
                                                    <h6 class="mb-0 text-muted">Sản phẩm không còn tồn tại</h6>
                                                    <small class="text-muted">SKU: {{ $item->product_sku ?? 'N/A' }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ number_format($item->price, 0, ',', '.') }} VNĐ</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $item->quantity }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-primary">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} VNĐ</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Tổng Kết Đơn Hàng</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($order->notes)
                                <h6>Ghi Chú</h6>
                                <p class="text-muted">{{ $order->notes }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <tr>
                                        <td>Tạm tính:</td>
                                        <td class="text-end">{{ number_format($order->orderItems->sum(fn($item) => $item->price * $item->quantity), 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                    @if($order->shipping_fee > 0)
                                    <tr>
                                        <td>Phí giao hàng:</td>
                                        <td class="text-end">{{ number_format($order->shipping_fee, 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                    @endif
                                    @if($order->discount_amount > 0)
                                    <tr>
                                        <td>Giảm giá:</td>
                                        <td class="text-end text-success">-{{ number_format($order->discount_amount, 0, ',', '.') }} VNĐ</td>
                                    </tr>
                                    @endif
                                    <tr class="table-active">
                                        <td><strong>Tổng cộng:</strong></td>
                                        <td class="text-end"><strong class="text-primary fs-5">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('profile.orders') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Quay Lại
                        </a>
                        @if($order->status === 'pending')
                            <div>
                                <button type="button" class="btn btn-outline-danger me-2" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                                    <i class="fas fa-times me-2"></i>Hủy Đơn
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
@if($order->status === 'pending')
<div class="modal fade" id="cancelOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hủy Đơn Hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn hủy đơn hàng #{{ $order->id }}?</p>
                <p class="text-muted small">Hành động này không thể hoàn tác.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <form method="POST" action="{{ route('profile.order.cancel', $order->id) }}" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger">Hủy Đơn Hàng</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.badge.fs-6 {
    font-size: 0.9rem !important;
    padding: 0.4em 0.8em;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.025);
}

.breadcrumb {
    background-color: transparent;
    padding: 0;
    margin-bottom: 0;
}

.breadcrumb-item a {
    color: #0d6efd;
    text-decoration: none;
}

.breadcrumb-item a:hover {
    text-decoration: underline;
}

address {
    line-height: 1.6;
}

.list-group-item.active {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.modal-content {
    border-radius: 0.5rem;
}

dl.row {
    margin-bottom: 0;
}

dt, dd {
    margin-bottom: 0.5rem;
}
</style>
@endpush
@extends('layouts.master')

@section('content')
<div class="container my-5">
    <!-- Progress Breadcrumb -->
    <div class="checkout-progress">
        <ul class="progress-steps">
            <div class="progress-line" style="width: 100%;"></div>
            
            <li class="progress-step completed">
                <div class="progress-step-circle">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div class="progress-step-label">Giỏ hàng</div>
            </li>
            
            <li class="progress-step completed">
                <div class="progress-step-circle">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div class="progress-step-label">Thanh toán</div>
            </li>
            
            <li class="progress-step active completed">
                <div class="progress-step-circle">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="progress-step-label">Hoàn thành</div>
            </li>
        </ul>
    </div>

    <!-- Order Confirmation Content -->
    <div class="order-confirmation-wrapper">
        <div class="text-center mb-4">
            <div class="success-icon">
                <i class="bi bi-check-circle-fill text-success"></i>
            </div>
            <h2 class="fw-bold text-success mb-3">Đặt hàng thành công!</h2>
            <p class="text-muted fs-5">Cảm ơn bạn đã đặt hàng. Chúng tôi sẽ liên hệ với bạn sớm nhất.</p>
        </div>

        <div class="row">
            <!-- Order Info -->
            <div class="col-lg-8">
                <div class="order-info-card">
                    <h4 class="card-title mb-4">
                        <i class="bi bi-receipt text-primary me-2"></i>
                        Thông tin đơn hàng
                    </h4>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Mã đơn hàng:</span>
                                <span class="info-value fw-bold text-primary">#{{ $order->order_number }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Ngày đặt:</span>
                                <span class="info-value">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Tên khách hàng:</span>
                                <span class="info-value">{{ $order->customer_name }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <span class="info-label">Số điện thoại:</span>
                                <span class="info-value">{{ $order->customer_phone }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="info-item">
                            <span class="info-label">Địa chỉ giao hàng:</span>
                            <span class="info-value">
                                {{ $order->address }}, {{ $order->ward }}, {{ $order->district }}, {{ $order->city }}
                            </span>
                        </div>
                    </div>

                    @if($order->notes)
                    <div class="mb-3">
                        <div class="info-item">
                            <span class="info-label">Ghi chú:</span>
                            <span class="info-value">{{ $order->notes }}</span>
                        </div>
                    </div>
                    @endif

                    <hr>

                    <h5 class="mb-3">Sản phẩm đã đặt</h5>
                    <div class="order-items-list">
                        @foreach($order->items as $item)
                        <div class="order-item">
                            <div class="item-info">
                                <span class="item-name">{{ $item->product_name }}</span>
                                <span class="item-qty">x{{ $item->quantity }}</span>
                            </div>
                            <div class="item-price">{{ number_format($item->subtotal, 0, ',', '.') }}₫</div>
                        </div>
                        @endforeach
                    </div>

                    <div class="order-totals">
                        <div class="total-row">
                            <span>Tạm tính:</span>
                            <span>{{ number_format($order->subtotal, 0, ',', '.') }}₫</span>
                        </div>
                        <div class="total-row">
                            <span>Phí vận chuyển:</span>
                            <span class="text-success">{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee, 0, ',', '.') . '₫' : 'Miễn phí' }}</span>
                        </div>
                        <div class="total-row total">
                            <span>Tổng cộng:</span>
                            <span class="text-danger fw-bold">{{ number_format($order->total, 0, ',', '.') }}₫</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="col-lg-4">
                <div class="payment-info-card">
                    <h5 class="card-title mb-4">
                        <i class="bi bi-credit-card text-warning me-2"></i>
                        Thông tin thanh toán
                    </h5>

                    <div class="payment-method-box">
                        <div class="payment-method-name">
                            @if($payment_method === 'cod')
                                <i class="bi bi-cash-coin text-success me-2"></i>
                            @else
                                <i class="bi bi-credit-card text-primary me-2"></i>
                            @endif
                            <strong>{{ $payment_method_name }}</strong>
                        </div>

                        @if($payment_method === 'online')
                        <div class="alert alert-warning mt-3">
                            <h6 class="alert-heading">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Vui lòng hoàn tất thanh toán
                            </h6>
                            
                            @if($online_method === 'bank')
                            <div class="row">
                                <div class="col-md-7">
                                    <p class="mb-2"><strong>Thông tin chuyển khoản:</strong></p>
                                    <p class="mb-1">Ngân hàng: <strong>Vietcombank (VCB)</strong></p>
                                    <p class="mb-1">Số TK: <strong>1234567890</strong></p>
                                    <p class="mb-1">Chủ TK: <strong>CONG TY YEN SAO HOANG DANG</strong></p>
                                    <p class="mb-1">Số tiền: <strong class="text-danger">{{ number_format($order->total, 0, ',', '.') }}₫</strong></p>
                                    <p class="mb-0">Nội dung: <strong>{{ $order->order_number }}</strong></p>
                                </div>
                                <div class="col-md-5 text-center">
                                    <img src="https://img.vietqr.io/image/VCB-1234567890-compact2.png?amount={{ $order->total }}&addInfo={{ $order->order_number }}&accountName=CONG TY YEN SAO HOANG DANG" 
                                         alt="QR Code" 
                                         class="img-fluid" 
                                         style="max-width: 200px; border: 2px solid #17a2b8; border-radius: 8px; padding: 10px; background: white;">
                                    <p class="mt-2 mb-0"><small>Quét mã để thanh toán</small></p>
                                </div>
                            </div>
                            
                            @elseif($online_method === 'vnpay')
                            <div class="alert alert-success">
                                <h6 class="alert-heading">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Thanh toán thành công qua VNPay
                                </h6>
                                <p class="mb-1"><strong>Mã giao dịch:</strong> {{ $transaction_id ?? 'N/A' }}</p>
                                <p class="mb-1"><strong>Số tiền:</strong> <span class="text-success">{{ number_format($order->total, 0, ',', '.') }}₫</span></p>
                                <p class="mb-0"><strong>Trạng thái:</strong> <span class="badge bg-success">Đã thanh toán</span></p>
                            </div>
                            @endif

                            <div class="mt-3">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Đơn hàng sẽ được xử lý sau khi chúng tôi xác nhận thanh toán của bạn.
                                </small>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-info mt-3">
                            <p class="mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Bạn sẽ thanh toán bằng tiền mặt khi nhận hàng.
                            </p>
                        </div>
                        @endif
                    </div>

                    <div class="action-buttons mt-4">
                        <a href="/" class="btn btn-outline-primary w-100 mb-2">
                            <i class="bi bi-house-door me-2"></i>Về trang chủ
                        </a>
                        <a href="/products" class="btn btn-primary w-100">
                            <i class="bi bi-shop me-2"></i>Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.order-confirmation-wrapper {
    max-width: 1200px;
    margin: 0 auto;
}

.success-icon {
    font-size: 5rem;
    margin-bottom: 1rem;
}

.order-info-card,
.payment-info-card {
    background: white;
    border-radius: 12px;
    padding: 30px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
}

.card-title {
    font-size: 1.25rem;
    font-weight: bold;
    color: #2c3e50;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 15px;
}

.info-item {
    display: flex;
    margin-bottom: 10px;
}

.info-label {
    font-weight: 500;
    color: #6c757d;
    min-width: 150px;
}

.info-value {
    color: #2c3e50;
}

.order-items-list {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
}

.order-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #e0e0e0;
}

.order-item:last-child {
    border-bottom: none;
}

.item-info {
    flex: 1;
}

.item-name {
    font-weight: 500;
    color: #2c3e50;
}

.item-qty {
    color: #6c757d;
    margin-left: 10px;
}

.item-price {
    font-weight: 600;
    color: #2c3e50;
}

.order-totals {
    margin-top: 20px;
    padding-top: 15px;
    border-top: 2px solid #f0f0f0;
}

.total-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    font-size: 15px;
}

.total-row.total {
    font-size: 1.25rem;
    padding-top: 15px;
    margin-top: 10px;
    border-top: 2px solid #FFB300;
}

.payment-method-box {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
}

.payment-method-name {
    font-size: 1.1rem;
    margin-bottom: 10px;
}

@media (max-width: 992px) {
    .payment-info-card {
        margin-top: 20px;
    }
}
</style>

@vite(['resources/css/checkout.css'])

@endsection

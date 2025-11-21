@extends('layouts.master')

@section('content')
<div class="container my-5">
    <!-- Progress Breadcrumb -->
    <div class="checkout-progress">
        <ul class="progress-steps">
            <div class="progress-line" style="width: 16.5%;"></div>
            
            <li class="progress-step active completed">
                <div class="progress-step-circle">
                    <i class="bi bi-cart-check-fill"></i>
                </div>
                <div class="progress-step-label">Giỏ hàng</div>
            </li>
            
            <li class="progress-step">
                <div class="progress-step-circle">
                    <i class="bi bi-credit-card"></i>
                </div>
                <div class="progress-step-label">Thanh toán</div>
            </li>
            
            <li class="progress-step">
                <div class="progress-step-circle">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="progress-step-label">Hoàn thành</div>
            </li>
        </ul>
    </div>

    @php 
        $cart = session('cart', []); 
        $subtotal = 0;
        foreach($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        $shipping = 0; // Free shipping
        $total = $subtotal + $shipping;
    @endphp

    @if(empty($cart))
        <div class="cart-empty">
            <i class="bi bi-cart-x"></i>
            <h3>Giỏ hàng của bạn đang trống</h3>
            <a href="/products" class="btn btn-primary btn-lg">
                <i class="bi bi-shop me-2"></i>Tiếp tục mua sắm
            </a>
        </div>
    @else
        <div class="row">
            <!-- Left: Cart Items -->
            <div class="col-lg-8">
                <!-- Table Header -->
                <div class="row cart-table-header">
                    <div class="col-5">SẢN PHẨM</div>
                    <div class="col-2 text-center">GIÁ</div>
                    <div class="col-2 text-center">SỐ LƯỢNG</div>
                    <div class="col-2 text-end">TẠM TÍNH</div>
                    <div class="col-1"></div>
                </div>

                <!-- Cart Items -->
                @foreach($cart as $id => $item)
                <div class="row cart-item-row align-items-center" data-item-id="{{ $id }}" data-price="{{ $item['price'] }}">
                    <div class="col-5">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $item['image'] ? asset('storage/'.$item['image']) : asset('images/products/product-1.jpg') }}" 
                                 alt="{{ $item['name'] }}" 
                                 class="cart-item-image">
                            <div>
                                <a href="/products/{{ $id }}" class="cart-item-name">{{ $item['name'] }}</a>
                                @if(isset($item['weight']) && $item['weight'])
                                <div class="mt-1">
                                    <span class="badge" style="background-color: #28a745; color: white; font-size: 0.75rem;">
                                        <i class="bi bi-box-seam me-1"></i>{{ $item['weight'] }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-2 text-center">
                        <span class="cart-item-price">{{ number_format($item['price'], 0, ',', '.') }}₫</span>
                    </div>
                    <div class="col-2 text-center">
                        <div class="cart-quantity-control mx-auto">
                            <button type="button" class="qty-decrease">-</button>
                            <input type="number" 
                                   class="qty-input" 
                                   value="{{ $item['quantity'] }}" 
                                   min="1" 
                                   max="999"
                                   data-item-id="{{ $id }}">
                            <button type="button" class="qty-increase">+</button>
                        </div>
                    </div>
                    <div class="col-2 text-end">
                        <span class="item-subtotal fw-bold">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫</span>
                    </div>
                    <div class="col-1 text-end">
                        <form action="/cart/remove/{{ $id }}" method="POST" class="d-inline remove-item-form">
                            @csrf
                            <button type="submit" class="cart-remove-btn" title="Xóa sản phẩm">
                                <i class="bi bi-x-circle"></i>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Right: Cart Summary -->
            <div class="col-lg-4">
                <div class="cart-summary-box">
                    <h3 class="cart-summary-title">Tổng cộng giỏ hàng</h3>
                    
                    <div class="cart-summary-row subtotal">
                        <span>Tạm tính</span>
                        <span id="cart-subtotal" class="fw-bold">{{ number_format($subtotal, 0, ',', '.') }}₫</span>
                    </div>
                    
                    <div class="cart-summary-row shipping">
                        <div>
                            <div>Vận chuyển</div>
                            <div class="mt-2">
                                <small class="text-muted">Giao hàng đến <strong>Hồ Chí Minh</strong>.</small><br>
                                <small class="cart-change-address">Đổi địa chỉ</small>
                            </div>
                        </div>
                        <span>Giao hàng miễn phí</span>
                    </div>
                    
                    <div class="cart-summary-row total">
                        <span>Tổng</span>
                        <span id="cart-total">{{ number_format($total, 0, ',', '.') }}₫</span>
                    </div>
                    
                    <a href="/checkout" class="cart-checkout-btn mt-4 d-block text-center text-decoration-none">
                        Tiến hành thanh toán
                    </a>
                    
                    <!-- Discount Code Section -->
                    <div class="cart-discount-section">
                        <div class="cart-discount-title">
                            <i class="bi bi-tag"></i>
                            Mã ưu đãi
                        </div>
                        <div class="cart-discount-input">
                            <input type="text" placeholder="Nhập mã giảm giá" class="form-control">
                            <button type="button">Áp dụng</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle quantity increase/decrease
    document.querySelectorAll('.qty-decrease, .qty-increase').forEach(btn => {
        btn.addEventListener('click', function() {
            const control = this.closest('.cart-quantity-control');
            const input = control.querySelector('.qty-input');
            let value = parseInt(input.value);
            
            if (this.classList.contains('qty-decrease') && value > 1) {
                value--;
            } else if (this.classList.contains('qty-increase')) {
                value++;
            }
            
            input.value = value;
            updateQuantity(input);
        });
    });
    
    // Handle direct input change
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            let value = parseInt(this.value);
            if (isNaN(value) || value < 1) {
                this.value = 1;
            }
            updateQuantity(this);
        });
    });
    
    // Update quantity function
    function updateQuantity(input) {
        const itemId = input.dataset.itemId;
        const quantity = parseInt(input.value);
        const row = input.closest('.cart-item-row');
        const price = parseFloat(row.dataset.price);
        
        // Update item subtotal
        const itemSubtotal = price * quantity;
        row.querySelector('.item-subtotal').textContent = formatCurrency(itemSubtotal);
        
        // Update cart totals
        updateCartTotals();
        
        // Send AJAX request to update server
        fetch(`/cart/update/${itemId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart count in header if needed
                const cartBadge = document.getElementById('cart-count-badge');
                if (cartBadge && data.cart_count !== undefined) {
                    cartBadge.textContent = data.cart_count;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    
    // Update cart totals
    function updateCartTotals() {
        let subtotal = 0;
        
        document.querySelectorAll('.cart-item-row').forEach(row => {
            const price = parseFloat(row.dataset.price);
            const quantity = parseInt(row.querySelector('.qty-input').value);
            subtotal += price * quantity;
        });
        
        const shipping = 0; // Free shipping
        const total = subtotal + shipping;
        
        document.getElementById('cart-subtotal').textContent = formatCurrency(subtotal);
        document.getElementById('cart-total').textContent = formatCurrency(total);
    }
    
    // Format currency
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'decimal',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount) + '₫';
    }
    
    // Handle remove item
    document.querySelectorAll('.remove-item-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                e.preventDefault();
            }
        });
    });
});
</script>

@vite(['resources/css/cart.css'])

@endsection

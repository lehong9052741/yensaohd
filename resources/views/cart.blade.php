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
        // Get cart items from database
        $cartItems = App\Models\CartItem::with('product')
            ->where(function($query) {
                if (auth()->check()) {
                    // For logged in users, get items by user_id OR session_id (for items added before login)
                    $query->where(function($q) {
                        $q->where('user_id', auth()->id())
                          ->orWhere(function($q2) {
                              $sessionId = session('cart_session_id');
                              if ($sessionId) {
                                  $q2->where('session_id', $sessionId)
                                     ->whereNull('user_id');
                              }
                          });
                    });
                } else {
                    $sessionId = session('cart_session_id');
                    if ($sessionId) {
                        $query->where('session_id', $sessionId)
                              ->whereNull('user_id');
                    } else {
                        $query->whereRaw('1 = 0'); // No items
                    }
                }
            })
            ->get();
        
        // Format for view compatibility
        $cart = [];
        $subtotal = 0;
        foreach($cartItems as $item) {
            if ($item->product) {
                $cart[$item->product_id] = [
                    'id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'image' => $item->product->image,
                    'weight' => $item->product->weight,
                ];
                $subtotal += $item->price * $item->quantity;
            }
        }
        
        $shipping = 0; // Free shipping
        $total = $subtotal + $shipping;
    @endphp

    @if(empty($cart))
        <div class="cart-empty">
            <i class="bi bi-cart-x"></i>
            <h3>Giỏ hàng của bạn đang trống</h3>
            <a href="/products" class="btn btn-primary btn-lg">
                Tiếp tục mua sắm
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
    // Function to attach dropdown event listeners (defined globally in this scope)
    function attachDropdownListeners() {
        const dropdown = document.getElementById('cart-dropdown-content');
        if (!dropdown) return;
        
        // Remove old listeners by cloning
        const newDropdown = dropdown.cloneNode(true);
        dropdown.parentNode.replaceChild(newDropdown, dropdown);
        
        newDropdown.addEventListener('submit', function(e) {
            if (e.target.classList.contains('remove-cart-item-form')) {
                e.preventDefault();
                
                const form = e.target;
                const cartItem = form.closest('.cart-item');
                const itemId = cartItem.dataset.itemId;
                
                // Add fade out animation
                cartItem.style.transition = 'opacity 0.3s ease';
                cartItem.style.opacity = '0';
                
                // Send AJAX request
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update cart page if we're on it
                        const cartPageRow = document.querySelector(`.cart-item-row[data-item-id="${itemId}"]`);
                        if (cartPageRow) {
                            cartPageRow.style.transition = 'opacity 0.3s ease';
                            cartPageRow.style.opacity = '0';
                            setTimeout(() => {
                                cartPageRow.remove();
                                updateCartTotals();
                                
                                // Check if cart is empty
                                const remainingItems = document.querySelectorAll('.cart-item-row');
                                if (remainingItems.length === 0) {
                                    window.location.reload();
                                }
                            }, 300);
                        }
                        
                        // Remove item after animation
                        setTimeout(() => {
                            cartItem.remove();
                            
                            // Update cart count in badge
                            const cartBadge = document.getElementById('cart-count-badge');
                            const dropdownCount = document.getElementById('dropdown-cart-count');
                            
                            if (cartBadge) {
                                cartBadge.textContent = data.cart_count;
                                if (data.cart_count === 0) {
                                    cartBadge.classList.add('d-none');
                                }
                            }
                            
                            if (dropdownCount) {
                                dropdownCount.textContent = data.cart_count;
                            }
                            
                            // Check if cart is empty in dropdown
                            const remainingItems = document.querySelectorAll('#dropdown-cart-items .cart-item');
                            if (remainingItems.length === 0) {
                                // Show empty message
                                const cartItemsContainer = document.getElementById('dropdown-cart-items');
                                const footer = document.getElementById('dropdown-cart-footer');
                                
                                if (cartItemsContainer) {
                                    cartItemsContainer.innerHTML = `
                                        <div class="text-center py-4" id="empty-cart-message" style="color: #666;">
                                            <i class="bi bi-cart" style="font-size: 3rem; color: #999;"></i>
                                            <p class="mt-2 mb-0">Giỏ hàng trống</p>
                                        </div>
                                    `;
                                }
                                
                                if (footer) {
                                    footer.remove();
                                }
                            } else {
                                // Update total if items remain
                                updateDropdownTotal();
                            }
                        }, 300);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    cartItem.style.opacity = '1';
                });
            }
        });
    }
    
    // Function to update dropdown total
    function updateDropdownTotal() {
        const remainingItems = document.querySelectorAll('#dropdown-cart-items .cart-item');
        let total = 0;
        
        remainingItems.forEach(item => {
            const price = parseFloat(item.dataset.price) || 0;
            const quantity = parseInt(item.dataset.quantity) || 0;
            total += price * quantity;
        });
        
        const totalElement = document.getElementById('dropdown-cart-total');
        if (totalElement) {
            totalElement.textContent = new Intl.NumberFormat('vi-VN').format(total) + '₫';
        }
    }
    
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
                    if (data.cart_count === 0) {
                        cartBadge.classList.add('d-none');
                    } else {
                        cartBadge.classList.remove('d-none');
                    }
                }
                
                // Update cart dropdown HTML
                const cartDropdownContent = document.getElementById('cart-dropdown-content');
                if (cartDropdownContent && data.cart_html) {
                    cartDropdownContent.innerHTML = data.cart_html;
                    // Re-attach event listeners to new dropdown content
                    if (typeof window.attachCartDropdownListeners === 'function') {
                        window.attachCartDropdownListeners();
                    }
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
    
    // Handle remove item with AJAX
    document.querySelectorAll('.remove-item-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const row = this.closest('.cart-item-row');
            const itemId = row.dataset.itemId;
            
            console.log('Removing item:', itemId, 'Action:', this.action);
            
            // Add fade out animation
            row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            row.style.opacity = '0';
            row.style.transform = 'translateX(20px)';
            
            // Send AJAX request to delete from database
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Remove the row after animation
                    setTimeout(() => {
                        row.remove();
                        
                        // Check if cart is empty
                        const remainingItems = document.querySelectorAll('.cart-item-row');
                        if (remainingItems.length === 0) {
                            // Reload to show empty cart message
                            window.location.reload();
                        } else {
                            // Update cart totals
                            updateCartTotals();
                        }
                        
                        // Update cart count in header
                        const cartBadge = document.getElementById('cart-count-badge');
                        if (cartBadge && data.cart_count !== undefined) {
                            cartBadge.textContent = data.cart_count;
                            if (data.cart_count === 0) {
                                cartBadge.classList.add('d-none');
                            }
                        }
                        
                        // Update cart dropdown in header
                        const cartDropdownContent = document.getElementById('cart-dropdown-content');
                        if (cartDropdownContent && data.cart_html) {
                            cartDropdownContent.innerHTML = data.cart_html;
                            
                            // Re-attach event listeners for dropdown after updating HTML
                            attachDropdownListeners();
                        }
                    }, 300);
                    
                    // Show success toast with red background for delete action
                    showToast('danger', data.message || 'Đã xóa sản phẩm khỏi giỏ hàng');
                } else {
                    // Revert animation on error
                    row.style.opacity = '1';
                    row.style.transform = 'translateX(0)';
                    showToast('error', data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                row.style.opacity = '1';
                row.style.transform = 'translateX(0)';
                showToast('error', 'Không thể xóa sản phẩm');
            });
        });
    });
    
    // Attach dropdown listeners on page load
    attachDropdownListeners();
    
    // Toast notification function
    function showToast(type, message) {
        const toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) return;
        
        const toastId = 'toast-' + Date.now();
        const bgClass = type === 'danger' ? 'bg-danger' : (type === 'success' ? 'bg-success' : 'bg-danger');
        const icon = type === 'danger' ? 'bi-trash' : (type === 'success' ? 'bi-check-circle' : 'bi-x-circle');
        
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi ${icon} me-2"></i>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
        
        // Remove toast after it's hidden
        toastElement.addEventListener('hidden.bs.toast', function() {
            this.remove();
        });
    }
});
</script>

@vite(['resources/css/cart.css'])

@endsection

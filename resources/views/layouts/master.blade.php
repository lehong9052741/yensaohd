<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Yến Sào Hoàng Đăng</title>
    <link rel="icon" type="image/png" href="{{ asset('images/banners/logo.png') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/footer.css', 'resources/js/footer.js', 'resources/css/product-detail.css', 'resources/css/checkout.css', 'resources/css/responsive.css'])
</head>
<body>
    @include('partials.header')

    {{-- Toast notifications --}}
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;">
        @if(session('success'))
            <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-x-circle me-2"></i>{{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if(session('cart_success'))
            <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle me-2"></i>{{ session('cart_success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
        @if(session('cart_removed'))
            <div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-trash me-2"></i>{{ session('cart_removed') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    {{-- Flash messages --}}
    @if($errors->any())
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <main class="py-4">
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Initialize and show toasts
        document.addEventListener('DOMContentLoaded', function() {
            const toastElements = document.querySelectorAll('.toast');
            toastElements.forEach(function(toastEl) {
                const toast = new bootstrap.Toast(toastEl, {
                    autohide: true,
                    delay: 3000
                });
                toast.show();
            });
            
            // Initialize cart dropdown event listeners
            attachCartDropdownListeners();
        });
        
        // Global function to attach cart dropdown event listeners
        window.attachCartDropdownListeners = function() {
            const dropdown = document.getElementById('cart-dropdown-content');
            if (!dropdown) return;
            
            // Remove old listeners by cloning and replacing the element
            const newDropdown = dropdown.cloneNode(true);
            dropdown.parentNode.replaceChild(newDropdown, dropdown);
            
            // Attach new listeners
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
                                
                                // Check if cart is empty
                                const remainingItems = document.querySelectorAll('#dropdown-cart-items .cart-item');
                                if (remainingItems.length === 0) {
                                    // Show empty message
                                    const cartItemsContainer = document.getElementById('dropdown-cart-items');
                                    const footer = document.getElementById('dropdown-cart-footer');
                                    
                                    if (cartItemsContainer) {
                                        cartItemsContainer.innerHTML = `
                                            <div class="text-center py-4" style="color: #666;">
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
                                
                                // Show toast notification for successful deletion
                                showDropdownToast('danger', data.message || 'Đã xóa sản phẩm khỏi giỏ hàng');
                            }, 300);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        cartItem.style.opacity = '1';
                    });
                }
            });
            
            // Function to show toast notification
            function showDropdownToast(type, message) {
                const toastContainer = document.querySelector('.toast-container');
                if (!toastContainer) return;
                
                const toastId = 'toast-' + Date.now();
                const bgClass = type === 'danger' ? 'bg-danger' : 'bg-success';
                const icon = type === 'danger' ? 'bi-trash' : 'bi-check-circle';
                
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
                
                toastElement.addEventListener('hidden.bs.toast', function() {
                    this.remove();
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
        };
    </script>
</body>
</html>

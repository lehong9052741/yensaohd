@extends('layouts.master')

@section('content')
<div class="container my-5 product-padding">
    <!-- Page Header -->
    <div class="mb-4">
        <div class="category-title-wrapper-product">
            <h2 class="category-title mb-0">
                @if(request('search'))
                    Kết quả tìm kiếm: "{{ request('search') }}"
                @elseif(request('category'))
                    {{ request('category') }}
                @else
                    Tất cả sản phẩm
                @endif
            </h2>
        </div>
        
        <!-- Search Bar -->
        <div class="row mb-4">
            <div class="col-md-6 text-start">
                <p class="text-muted mt-2">Tổng có <strong>{{ $products->total() }}</strong> sản phẩm</p>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="product-row">
            @foreach($products as $product)
            <div class="product-col">
                <div class="product-block-card">
                    <div class="product-block-image-wrapper" style="cursor: pointer;">
                        <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/products/product-1.jpg') }}" 
                             class="product-block-image" alt="{{ $product->name }}"
                             onclick="window.location.href='{{ url('/products/' . $product->id) }}'">
                        @if($product->has_sale)
                        <div class="product-block-discount" onclick="window.location.href='{{ url('/products/' . $product->id) }}'">-{{ $product->discount_percent }}%</div>
                        @endif
                        <form action="{{ url('/cart/add/' . $product->id) }}" method="POST" class="add-to-cart-form d-inline" data-product-name="{{ $product->name }}">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="product-block-cart-icon">
                                <i class="bi bi-cart-plus text-white fs-4"></i>
                            </button>
                        </form>
                    </div>
                    <div class="product-block-body" style="cursor: pointer;">
                        <h5 class="product-block-title" onclick="window.location.href='{{ url('/products/' . $product->id) }}'">{{ $product->name }}</h5>
                        <div class="product-block-price-section text-end" onclick="window.location.href='{{ url('/products/' . $product->id) }}'">
                            @if($product->has_sale)
                            <div>
                                <span class="product-block-price-old">{{ number_format($product->original_price ?? $product->price) }}₫</span>
                                <span class="product-block-price-new">{{ number_format($product->display_price) }}₫</span>
                            </div>
                            @else
                            <div><span class="product-block-price-new">{{ number_format($product->price) }}₫</span></div>
                            @endif
                            <div class="mt-2">
                                @if($product->quantity > 0)
                                    <span class="badge bg-success">Còn {{ $product->quantity }} sản phẩm</span>
                                @else
                                    <span class="badge bg-danger">Hết hàng</span>
                                @endif
                            </div>
                        </div>
                        <button class="btn product-block-btn w-100" onclick="window.location.href='{{ url('/products/' . $product->id) }}'">Đặt hàng ngay</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-5 d-flex justify-content-center">
            {{ $products->withQueryString()->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
            <p class="text-muted mt-3 fs-5">Không tìm thấy sản phẩm nào</p>
            <a href="/products" class="btn btn-primary mt-2">
                <i class="bi bi-arrow-left"></i> Xem tất cả sản phẩm
            </a>
        </div>
    @endif
</div>

<!-- Related Products Section -->
@if(isset($relatedProducts) && $relatedProducts->count() > 0)
<div class="container my-5">
    <div class="category-title-wrapper-product">
        <h3 class="category-title mb-0">Sản Phẩm Liên Quan</h3>
    </div>
    
    <div id="relatedProductsCarousel" class="carousel slide mt-4 position-relative" data-bs-ride="carousel" data-bs-interval="3000" style="padding: 0 50px;">
        <div class="carousel-inner">
            @foreach($relatedProducts->chunk(2) as $index => $chunk)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                <div class="d-flex justify-content-center gap-3">
                    @foreach($chunk as $product)
                    <div style="width: 18%; min-width: 160px;">
                        <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                            <div style="position: relative; overflow: hidden;">
                                <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/products/product-1.jpg') }}" 
                                     class="card-img-top" 
                                     style="height: 180px; object-fit: cover; cursor: pointer; transition: transform 0.3s ease;"
                                     onmouseover="this.style.transform='scale(1.1)'"
                                     onmouseout="this.style.transform='scale(1)'"
                                     onclick="window.location.href='{{ url('/products/' . $product->id) }}'"
                                     alt="{{ $product->name }}">
                                @if($product->has_sale)
                                <div style="position: absolute; top: 8px; right: 8px; background-color: #dc3545; color: white; padding: 4px 8px; border-radius: 5px; font-weight: bold; font-size: 0.75rem; z-index: 5;">
                                    -{{ $product->discount_percent }}%
                                </div>
                                @endif
                            </div>
                            <div class="card-body p-2" style="cursor: pointer;" onclick="window.location.href='{{ url('/products/' . $product->id) }}'">
                                <h6 class="card-title mb-2" style="font-size: 0.85rem; line-height: 1.3; height: 2.6rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">{{ $product->name }}</h6>
                                <div class="text-end">
                                    @if($product->has_sale)
                                    <small class="text-muted text-decoration-line-through d-block" style="font-size: 0.75rem;">{{ number_format($product->price) }}₫</small>
                                    <p class="text-danger fw-bold mb-0" style="font-size: 0.95rem;">{{ number_format($product->display_price) }}₫</p>
                                    @else
                                    <p class="text-dark fw-bold mb-0" style="font-size: 0.95rem;">{{ number_format($product->price) }}₫</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        
        @if($relatedProducts->count() > 2)
        <button class="carousel-control-prev" type="button" data-bs-target="#relatedProductsCarousel" data-bs-slide="prev" style="left: 5px; width: auto;">
            <span class="d-flex align-items-center justify-content-center rounded-circle" style="background-color: rgba(200, 200, 200, 0.8); width: 35px; height: 35px;" aria-hidden="true">
                <i class="bi bi-chevron-left text-white fs-5"></i>
            </span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#relatedProductsCarousel" data-bs-slide="next" style="right: 5px; width: auto;">
            <span class="d-flex align-items-center justify-content-center rounded-circle" style="background-color: rgba(200, 200, 200, 0.8); width: 35px; height: 35px;" aria-hidden="true">
                <i class="bi bi-chevron-right text-white fs-5"></i>
            </span>
            <span class="visually-hidden">Next</span>
        </button>
        @endif
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle add to cart forms with AJAX
    const cartForms = document.querySelectorAll('.add-to-cart-form');
    
    cartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const formData = new FormData(this);
            const productName = this.dataset.productName;
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show toast notification
                    showToast('success', data.message || 'Đã thêm "' + productName + '" vào giỏ hàng');
                    
                    // Update cart count badge
                    const cartBadge = document.getElementById('cart-count-badge');
                    if (cartBadge) {
                        cartBadge.textContent = data.cart_count;
                        if (data.cart_count > 0) {
                            cartBadge.classList.remove('d-none');
                        }
                    }
                    
                    // Update cart dropdown HTML
                    const cartDropdown = document.getElementById('cart-dropdown');
                    if (cartDropdown && data.cart_html) {
                        cartDropdown.innerHTML = data.cart_html;
                    }
                } else {
                    showToast('error', data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'Không thể thêm vào giỏ hàng');
            });
        });
    });
    
    // Toast notification function
    function showToast(type, message) {
        const toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) return;
        
        const toastId = 'toast-' + Date.now();
        const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
        const icon = type === 'success' ? 'bi-check-circle' : 'bi-x-circle';
        
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

@endsection

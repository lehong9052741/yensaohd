@extends('layouts.master')

@section('content')
    <!-- Banner / Carousel -->
    <div class="container">
        <div id="homeCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('images/banners/slide1.jpg') }}" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h3 class="display-4 fw-bold">Tinh Hoa Từ Tổ Yến</h3>
                        <p class="lead">Chất lượng từ tâm</p>
                    </div>
                </div>
            <div class="carousel-item">
                <img src="{{ asset('images/banners/slide2.jpg') }}" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/banners/slide3.jpg') }}" class="d-block w-100" alt="...">
            </div>
        </div>
    </div>

    <!-- Sản phẩm bán chạy -->
    <section class="mb-5 position-relative best-seller-section">
        <div class="container-fluid px-4">
            <h2 class="mb-3 fw-bold text-start best-seller-title">
                <i class="bi bi-fire"></i> Sản Phẩm Bán Chạy
            </h2>
        </div>
        <hr class="mb-4 mx-0 best-seller-divider">
        <div class="container">
            <div id="bestSellerCarousel" class="carousel slide position-relative best-seller-carousel" data-bs-ride="carousel" data-bs-interval="4000" style="padding: 0 50px;">
                <!-- Carousel Indicators -->
                <div class="carousel-indicators">
                    @foreach($bestSellers->chunk(4) as $index => $chunk)
                    <button type="button" data-bs-target="#bestSellerCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : 'false' }}"></button>
                    @endforeach
                </div>

                <div class="carousel-inner">
                    @foreach($bestSellers->chunk(4) as $index => $chunk)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <div class="d-flex justify-content-center gap-3">
                            @foreach($chunk as $product)
                            <div style="width: 48%; min-width: 160px;">
                                <a href="{{ url('/products/' . $product->id) }}" class="text-decoration-none">
                                    <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                                        <div style="position: relative; overflow: hidden;">
                                            <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/products/product-1.jpg') }}" 
                                                 class="card-img-top" 
                                                 style="height: 200px; object-fit: cover;"
                                                 alt="{{ $product->name }}">
                                            @if($product->has_sale)
                                            <div style="position: absolute; top: 8px; right: 8px; background-color: #dc3545; color: white; padding: 4px 8px; border-radius: 5px; font-weight: bold; font-size: 0.75rem; z-index: 5;">
                                                -{{ $product->discount_percent }}%
                                            </div>
                                            @endif
                                            @if($product->weight)
                                            <div style="position: absolute; bottom: 8px; right: 8px; z-index: 5;">
                                                <span class="badge" style="background-color: #28a745; color: white; font-size: 0.75rem;">
                                                    <i class="bi bi-box-seam me-1"></i>{{ $product->weight }}
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="card-body p-3">
                                            <h6 class="card-title mb-2" style="font-size: 0.9rem; line-height: 1.3; height: 2.6rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">{{ $product->name }}</h6>
                                            <div class="text-end">
                                                @if($product->has_sale)
                                                <small class="text-muted text-decoration-line-through d-block" style="font-size: 0.75rem;">{{ number_format($product->original_price ?? $product->price) }}₫</small>
                                                <p class="text-danger fw-bold mb-0" style="font-size: 0.95rem;">{{ number_format($product->sale_price) }}₫</p>
                                                @else
                                                <p class="text-dark fw-bold mb-0" style="font-size: 0.95rem;">{{ number_format($product->price) }}₫</p>
                                                @endif
                                            </div>
                                            <div class="text-end mt-2">
                                                <small class="text-muted fst-italic" style="font-size: 0.75rem;">Đã bán: {{ $product->sold_count }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($bestSellers->count() > 4)
                <button class="carousel-control-prev" type="button" data-bs-target="#bestSellerCarousel" data-bs-slide="prev" style="left: 5px; width: auto;">
                    <span class="d-flex align-items-center justify-content-center rounded-circle" style="background-color: rgba(200, 200, 200, 0.8); width: 35px; height: 35px;" aria-hidden="true">
                        <i class="bi bi-chevron-left text-white fs-5"></i>
                    </span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#bestSellerCarousel" data-bs-slide="next" style="right: 5px; width: auto;">
                    <span class="d-flex align-items-center justify-content-center rounded-circle" style="background-color: rgba(200, 200, 200, 0.8); width: 35px; height: 35px;" aria-hidden="true">
                        <i class="bi bi-chevron-right text-white fs-5"></i>
                    </span>
                    <span class="visually-hidden">Next</span>
                </button>
                @endif
            </div>
        </div>
    </section>

    <!-- Info Block with Carousel -->
    <section class="mb-5 py-5 info-section">
        <div class="container">
            <div class="row align-items-center">
                <!-- Column 1: Info -->
                <div class="col-md-5">
                    <div class="info-content text-center text-md-start">
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/banners/trang-tri-yen-sao.png') }}" alt="Trang trí" class="info-decoration-icon">
                        </div>
                        <h2 class="info-title fw-bold mb-3">Yến Sào Hoàng Đăng</h2>
                        <div class="info-description text-start mb-4">
                            <p class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Yến sào thiên nhiên 100% từ đảo Hòn Nội</p>
                            <p class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Quy trình sản xuất khép kín, đảm bảo vệ sinh an toàn thực phẩm</p>
                            <p class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Giá cả hợp lý, chất lượng vượt trội</p>
                            <p class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i>Giao hàng nhanh chóng, bảo hành đổi trả 7 ngày</p>
                            <p class="mb-0"><i class="bi bi-check-circle-fill text-success me-2"></i>Đội ngũ tư vấn chuyên nghiệp, nhiệt tình</p>
                        </div>
                        <div class="text-center">
                            <a href="/products" class="btn btn-primary btn-lg fw-bold info-view-all-btn">
                                <i class="bi bi-eye me-2"></i>Xem tất cả sản phẩm
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Column 2: Carousel -->
                <div class="col-md-7">
                    <div id="infoCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-indicators info-carousel-indicators">
                            <button type="button" data-bs-target="#infoCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
                            <button type="button" data-bs-target="#infoCarousel" data-bs-slide-to="1"></button>
                            <button type="button" data-bs-target="#infoCarousel" data-bs-slide-to="2"></button>
                            <button type="button" data-bs-target="#infoCarousel" data-bs-slide-to="3"></button>
                        </div>
                        <div class="carousel-inner rounded-3 shadow">
                            <div class="carousel-item active">
                                <img src="{{ asset('images/banners/hop-to-yen-nha-tinh-che-100g-1.jpg') }}" class="d-block w-100 info-carousel-image" alt="Slide 1">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('images/banners/hop-to-yen-nha-tinh-che-100g-1.jpg') }}" class="d-block w-100 info-carousel-image" alt="Slide 2">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('images/banners/hop-to-yen-nha-tinh-che-100g-1.jpg') }}" class="d-block w-100 info-carousel-image" alt="Slide 3">
                            </div>
                            <div class="carousel-item">
                                <img src="{{ asset('images/banners/hop-to-yen-nha-tinh-che-100g-1.jpg') }}" class="d-block w-100 info-carousel-image" alt="Slide 4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Blocks -->
    <div class="container">
        <!-- Yến Thô -->
        @if($yenTho->count() > 0)
        <section class="mb-5">
            <div class="category-title-wrapper">
                <h2 class="category-title mb-0">Yến Thô Tự Nhiên</h2>
            </div>
            <div class="product-row">
                @foreach($yenTho->take(4) as $product)
                <div class="product-col">
                    <div class="product-block-card">
                        <div class="product-block-image-wrapper" style="cursor: pointer;">
                            <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/products/product-1.jpg') }}" 
                                 class="product-block-image" alt="{{ $product->name }}"
                                 onclick="window.location.href='{{ url('/products/' . $product->id) }}'">
                            @if($product->has_sale)
                            <div class="product-block-discount" onclick="window.location.href='{{ url('/products/' . $product->id) }}'">-{{ $product->discount_percent }}%</div>
                            @endif
                            @if($product->weight)
                            <div style="position: absolute; bottom: 10px; right: 10px; z-index: 5;" onclick="window.location.href='{{ url('/products/' . $product->id) }}'">
                                <span class="badge" style="background-color: #28a745; color: white; font-size: 0.85rem;">
                                    <i class="bi bi-box-seam me-1"></i>{{ $product->weight }}
                                </span>
                            </div>
                            @endif
                            <form action="{{ url('/cart/add/' . $product->id) }}" method="POST" class="add-to-cart-form d-inline" 
                                  data-product-name="{{ $product->name }}"
                                  data-product-image="{{ $product->image ? asset('storage/'.$product->image) : asset('images/products/product-1.jpg') }}"
                                  data-product-price="{{ number_format($product->display_price ?? $product->price, 0, ',', '.') }}">
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
            <div class="text-end mt-3">
                <a href="/products?category=Yến Thô" class="text-decoration-none text-primary fw-bold">
                    Xem tất cả <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </section>
        @endif
    </div>

    <!-- Yến Tinh Chế -->
    @if($yenTinhChe->count() > 0)
    <section class="mb-5 py-4">
        <div class="container">
            <div class="category-title-wrapper">
                <h2 class="category-title mb-0">Yến Tinh Chế</h2>
            </div>
            <div class="product-row">
                @foreach($yenTinhChe->take(4) as $product)
                <div class="product-col">
                    <div class="product-block-card">
                        <div class="product-block-image-wrapper" style="cursor: pointer;">
                            <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/products/product-1.jpg') }}" 
                                 class="product-block-image" alt="{{ $product->name }}"
                                 onclick="window.location.href='{{ url('/products/' . $product->id) }}'">
                            @if($product->has_sale)
                            <div class="product-block-discount" onclick="window.location.href='{{ url('/products/' . $product->id) }}'">-{{ $product->discount_percent }}%</div>
                            @endif
                            @if($product->weight)
                            <div style="position: absolute; bottom: 10px; right: 10px; z-index: 5;" onclick="window.location.href='{{ url('/products/' . $product->id) }}'">
                                <span class="badge" style="background-color: #28a745; color: white; font-size: 0.85rem;">
                                    <i class="bi bi-box-seam me-1"></i>{{ $product->weight }}
                                </span>
                            </div>
                            @endif
                            <form action="{{ url('/cart/add/' . $product->id) }}" method="POST" class="add-to-cart-form d-inline" 
                                  data-product-name="{{ $product->name }}"
                                  data-product-image="{{ $product->image ? asset('storage/'.$product->image) : asset('images/products/product-1.jpg') }}"
                                  data-product-price="{{ number_format($product->display_price ?? $product->price, 0, ',', '.') }}">
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
            <div class="text-end mt-3">
                <a href="/products?category=Yến Tinh Chế" class="text-decoration-none text-primary fw-bold">
                    Xem tất cả <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Yến Chưng Sẵn -->
    <div class="container">
        @if($yenChungSan->count() > 0)
        <section class="mb-5">
            <div class="category-title-wrapper">
                <h2 class="category-title mb-0">Yến Chưng Sẵn</h2>
            </div>
            <div class="product-row">
                @foreach($yenChungSan->take(4) as $product)
                <div class="product-col">
                    <div class="product-block-card">
                        <div class="product-block-image-wrapper" style="cursor: pointer;">
                            <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/products/product-1.jpg') }}" 
                                 class="product-block-image" alt="{{ $product->name }}"
                                 onclick="window.location.href='{{ url('/products/' . $product->id) }}'">
                            @if($product->has_sale)
                            <div class="product-block-discount" onclick="window.location.href='{{ url('/products/' . $product->id) }}'">-{{ $product->discount_percent }}%</div>
                            @endif
                            @if($product->weight)
                            <div style="position: absolute; bottom: 10px; right: 10px; z-index: 5;" onclick="window.location.href='{{ url('/products/' . $product->id) }}'">
                                <span class="badge" style="background-color: #28a745; color: white; font-size: 0.85rem;">
                                    <i class="bi bi-box-seam me-1"></i>{{ $product->weight }}
                                </span>
                            </div>
                            @endif
                            <form action="{{ url('/cart/add/' . $product->id) }}" method="POST" class="add-to-cart-form d-inline" 
                                  data-product-name="{{ $product->name }}"
                                  data-product-image="{{ $product->image ? asset('storage/'.$product->image) : asset('images/products/product-1.jpg') }}"
                                  data-product-price="{{ number_format($product->display_price ?? $product->price, 0, ',', '.') }}">
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
            <div class="text-end mt-3">
                <a href="/products?category=Yến Chưng Sẵn" class="text-decoration-none text-primary fw-bold">
                    Xem tất cả <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </section>
        @endif
    </div>

    <!-- Hot Banner Section -->
    <section class="mb-5">
        <div class="container">
            <div class="hot-banner-wrapper">
                <img src="{{ asset('images/banners/section_hot_banner.png') }}" class="w-100 rounded shadow-sm" alt="Hot Banner">
            </div>
        </div>
    </section>

    <!-- Customer Reviews Carousel -->
    <div class="container">
        <section class="mb-5">
            <div class="text-center mb-4">
                <h2 class="fw-bold d-inline-block mb-0" style="border-bottom: 3px solid #dc3545; padding-bottom: 0.5rem; color: #936f03;">
                    <i class="bi bi-chat-quote me-2"></i>Ý Kiến Khách Hàng
                </h2>
            </div>
            @if(isset($customerReviews) && count($customerReviews) > 0)
            <div id="customerReviewsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                <div class="carousel-indicators">
                    @foreach(array_chunk($customerReviews, 3) as $index => $chunk)
                    <button type="button" data-bs-target="#customerReviewsCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : 'false' }}"></button>
                    @endforeach
                </div>
                <div class="carousel-inner">
                    @foreach(array_chunk($customerReviews, 3) as $index => $reviewChunk)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <div class="row g-4">
                            @foreach($reviewChunk as $review)
                            <div class="col-md-4">
                                <div class="review-card text-center p-4 h-100">
                                    <div class="customer-avatar mx-auto mb-3">
                                        <img src="{{ asset($review['avatar']) }}" alt="{{ $review['name'] }}" class="rounded-circle">
                                    </div>
                                    <h5 class="customer-name fw-bold mb-2">{{ $review['name'] }}</h5>
                                    <div class="rating mb-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review['rating'])
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @else
                                                <i class="bi bi-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="review-text text-muted fst-italic">
                                        "{{ $review['review'] }}"
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#customerReviewsCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#customerReviewsCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            @else
            <p class="text-center text-muted">Chưa có đánh giá nào.</p>
            @endif
        </section>
    </div>

    <!-- News Section -->
    <div class="container">
        <section class="mb-5">
            <div class="text-center mb-4">
                <h2 class="fw-bold d-inline-block mb-0" style="border-bottom: 3px solid #dc3545; padding-bottom: 0.5rem; color: #936f03;">
                    <i class="bi bi-newspaper me-2"></i>Tin Tức Nổi Bật
                </h2>
            </div>
            @php
                $latestNews = App\Models\News::orderBy('created_at', 'desc')->take(4)->get();
            @endphp
            @if($latestNews->count() > 0)
            <div class="row g-4">
                <!-- Featured News (Large) -->
                <div class="col-md-7">
                    @if($latestNews->first())
                    @php $featuredNews = $latestNews->first(); @endphp
                    <a href="{{ url('/news/' . $featuredNews->slug) }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm border-0 news-card" style="border-radius: 12px; overflow: hidden;">
                            <div style="position: relative; overflow: hidden; height: 400px;">
                                <img src="{{ $featuredNews->image ? asset('storage/'.$featuredNews->image) : asset('images/banners/logo.png') }}" 
                                     class="card-img-top" 
                                     style="height: 100%; width: 100%; object-fit: cover; transition: transform 0.3s ease;"
                                     alt="{{ $featuredNews->title }}">
                            </div>
                            <div class="card-body" style="padding: 1.5rem;">
                                <h4 class="card-title" style="font-size: 1.4rem; font-weight: 600; line-height: 1.4; margin-bottom: 1rem; color: #333;">
                                    {{ $featuredNews->title }}
                                </h4>
                                <p class="card-text text-muted" style="font-size: 0.95rem; line-height: 1.6;">
                                    {{ $featuredNews->excerpt ?? Str::limit(strip_tags($featuredNews->content), 150) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $featuredNews->created_at->format('d/m/Y') }}
                                    </small>
                                    <span class="text-danger fw-bold" style="font-size: 0.9rem;">
                                        Đọc tiếp <i class="bi bi-arrow-right"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                    @endif
                </div>

                <!-- Small News List (Right) -->
                <div class="col-md-5">
                    <div class="d-flex flex-column" style="gap: 1rem;">
                        @foreach($latestNews->skip(1)->take(3) as $news)
                        <a href="{{ url('/news/' . $news->slug) }}" class="text-decoration-none">
                            <div class="card shadow-sm border-0 news-card" style="border-radius: 10px; overflow: hidden;">
                                <div class="row g-0">
                                    <div class="col-5">
                                        <div style="height: 120px; overflow: hidden;">
                                            <img src="{{ $news->image ? asset('storage/'.$news->image) : asset('images/banners/logo.png') }}" 
                                                 style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;"
                                                 alt="{{ $news->title }}">
                                        </div>
                                    </div>
                                    <div class="col-7">
                                        <div class="card-body" style="padding: 1rem;">
                                            <h6 class="card-title mb-2" style="font-size: 0.95rem; line-height: 1.4; height: 4.2rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; color: #333; font-weight: 600;">
                                                {{ $news->title }}
                                            </h6>
                                            <small class="text-muted" style="font-size: 0.8rem;">
                                                <i class="bi bi-calendar3 me-1"></i>{{ $news->created_at->format('d/m/Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ url('/news') }}" class="btn btn-outline-danger btn-lg" style="text-transform: uppercase; font-weight: 600; border-width: 2px; padding: 0.6rem 2rem;">
                            XEM THÊM
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Chưa có tin tức nào.</p>
                </div>
            </div>
            @endif
        </section>
    </div>

     <!-- Brand Story Section -->
    <div class="container">
        <section class="mb-5 py-5 brand-story-section">
            <div class="row align-items-center">
                <!-- Left: Image -->
                <div class="col-md-4">
                    <div class="brand-story-image-wrapper">
                        <img src="{{ asset('images/products/nestbird.png') }}" alt="Nest Bird" class="img-transform">
                    </div>
                </div>
                
                <!-- Right: Content -->
                <div class="col-md-8">
                    <div class="brand-story-content">
                        <h2 class="brand-story-title text-center mb-4">
                            Yến sào dễ kiếm – An tâm khó tìm<br>
                            Thương hiệu có tâm – Mua sắm xứng tâm
                        </h2>
                        <div class="brand-story-text">
                            <p>
                                Trở thành một trong những công ty hàng đầu cung cấp các sản phẩm dinh dưỡng đến từ Yến sào: có uy tín, thương hiệu trong lĩnh vực Yến sào.
                            </p>
                            <p>
                                Là thương hiệu Việt Nam chấp cánh đưa nông sản Việt Nam nổi chung và Yến sào Việt Nam nổi riêng có tên trên bản đồ nông sản thế giới, sánh ngang với các thương hiệu nông sản nổi tiếng của các cường quốc nông nghiệp trên thế giới.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    </section>

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
                const productImage = this.dataset.productImage;
                const productPrice = this.dataset.productPrice;
                
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
                        // Show toast notification with product info
                        showToast('success', {
                            name: productName,
                            image: productImage,
                            price: productPrice
                        });
                        
                        // Update cart count badge
                        const cartBadge = document.getElementById('cart-count-badge');
                        if (cartBadge) {
                            cartBadge.textContent = data.cart_count;
                            if (data.cart_count > 0) {
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
        function showToast(type, data) {
            const toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) return;
            
            const toastId = 'toast-' + Date.now();
            
            let toastHtml = '';
            
            if (type === 'success' && typeof data === 'object' && data.name) {
                // Success toast with product info
                toastHtml = `
                    <div id="${toastId}" class="toast text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="4000" style="min-width: 350px;">
                        <div class="toast-body p-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-check-circle me-2 fs-5"></i>
                                <strong>Thêm sản phẩm thành công</strong>
                                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="d-flex align-items-center gap-3 mt-2">
                                <img src="${data.image}" alt="${data.name}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                <div class="flex-grow-1">
                                    <div class="fw-medium" style="font-size: 0.95rem;">${data.name}</div>
                                    <div class="mt-1" style="font-size: 0.9rem; opacity: 0.95;">${data.price}₫</div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                // Simple error toast
                const message = typeof data === 'string' ? data : data.message || 'Có lỗi xảy ra';
                const bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
                const icon = type === 'success' ? 'bi-check-circle' : 'bi-x-circle';
                
                toastHtml = `
                    <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="bi ${icon} me-2"></i>${message}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    </div>
                `;
            }
            
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

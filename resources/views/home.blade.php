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
            <div id="bestSellerCarousel" class="carousel slide position-relative best-seller-carousel" data-bs-ride="carousel" data-bs-interval="4000">
                <div class="carousel-inner">
                    @foreach($bestSellers->chunk(5) as $index => $chunk)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        <div class="row row-cols-2 row-cols-md-5 g-3">
                            @foreach($chunk as $key => $product)
                            <div class="col">
                                <a href="{{ url('/products/' . $product->id) }}" class="text-decoration-none">
                                    <div class="card h-100 shadow-sm position-relative product-card">
                                        @if($product->has_sale)
                                        <div class="discount-badge">
                                            <span class="badge bg-danger fs-6">-{{ $product->discount_percent }}%</span>
                                        </div>
                                        @endif
                                        <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/products/product-1.jpg') }}" 
                                             class="card-img-top product-image" alt="{{ $product->name }}">
                                        <div class="card-body p-3">
                                            <h6 class="card-title text-truncate mb-3 fw-bold text-start text-dark product-title">{{ $product->name }}</h6>
                                            <div class="price-section d-flex justify-content-between align-items-center mb-2">
                                                @if($product->has_sale)
                                                <p class="text-muted text-decoration-line-through mb-0 product-price-old">
                                                    {{ number_format($product->original_price ?? $product->price) }}₫
                                                </p>
                                                <p class="text-danger fw-bold mb-0 product-price-new">
                                                    {{ number_format($product->sale_price) }}₫
                                                </p>
                                                @else
                                                <p class="text-dark fw-bold mb-0 ms-auto product-price-new">
                                                    {{ number_format($product->price) }}₫
                                                </p>
                                                @endif
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted fst-italic product-sold-count">Đã bán: {{ $product->sold_count }}</small>
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
                @if($bestSellers->count() > 5)
                <button class="carousel-control-prev carousel-control-custom-prev" type="button" data-bs-target="#bestSellerCarousel" data-bs-slide="prev">
                    <span class="d-flex align-items-center justify-content-center rounded-circle carousel-control-icon" aria-hidden="true">
                        <i class="bi bi-chevron-left text-white fs-4"></i>
                    </span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next carousel-control-custom-next" type="button" data-bs-target="#bestSellerCarousel" data-bs-slide="next">
                    <span class="d-flex align-items-center justify-content-center rounded-circle carousel-control-icon" aria-hidden="true">
                        <i class="bi bi-chevron-right text-white fs-4"></i>
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
        <section class="mb-5">
            <div class="category-title-wrapper">
                <h2 class="category-title mb-0">Yến Thô Tự Nhiên</h2>
            </div>
            <div class="product-row">
                @for($i=1;$i<=4;$i++)
                <div class="product-col">
                    <div class="product-block-card">
                        <div class="product-block-image-wrapper">
                            <img src="{{ asset('images/products/product-1.jpg') }}" class="product-block-image" alt="Sản phẩm Thô">
                            @if($i % 2 == 0)
                            <div class="product-block-discount">-20%</div>
                            @endif
                            <div class="product-block-cart-icon">
                                <i class="bi bi-cart-plus text-white fs-4"></i>
                            </div>
                        </div>
                        <div class="product-block-body">
                            <h5 class="product-block-title">Sản phẩm Thô #{{ $i }}</h5>
                            <div class="product-block-price-section text-end">
                                @if($i % 2 == 0)
                                <div>
                                    <span class="product-block-price-old">1,000,000₫</span>
                                    <span class="product-block-price-new">800,000₫</span>
                                </div>
                                @else
                                <div><span class="product-block-price-new">1,000,000₫</span></div>
                                @endif
                                <div class="mt-2">
                                    @php $qty = rand(0, 50); @endphp
                                    @if($qty > 0)
                                        <span class="badge bg-success">Còn {{ $qty }} sản phẩm</span>
                                    @else
                                        <span class="badge bg-danger">Hết hàng</span>
                                    @endif
                                </div>
                            </div>
                            <a href="#" class="btn product-block-btn w-100">Đặt hàng ngay</a>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
            <div class="text-end mt-3">
                <a href="/products?category=Yến+Thô" class="text-decoration-none text-primary fw-bold">
                    Xem tất cả <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </section>
    </div>

    <section class="mb-5 py-4">
        <div class="container">
            <div class="category-title-wrapper">
                <h2 class="category-title mb-0">Yến Tinh Chế</h2>
            </div>
            <div class="product-row">
                @for($i=1;$i<=4;$i++)
                <div class="product-col">
                    <div class="product-block-card">
                        <div class="product-block-image-wrapper">
                            <img src="https://placehold.co/600x400/e8e8e8/333333?text=San+pham+Tinh+Che" class="product-block-image" alt="Sản phẩm Tinh Chế">
                            @if($i % 3 == 0)
                            <div class="product-block-discount">-15%</div>
                            @endif
                            <div class="product-block-cart-icon">
                                <i class="bi bi-cart-plus text-white fs-4"></i>
                            </div>
                        </div>
                        <div class="product-block-body">
                            <h5 class="product-block-title">Sản phẩm Tinh Chế #{{ $i }}</h5>
                            <div class="product-block-price-section text-end">
                                @if($i % 3 == 0)
                                <div>
                                    <span class="product-block-price-old">1,500,000₫</span>
                                    <span class="product-block-price-new">1,275,000₫</span>
                                </div>
                                @else
                                <div><span class="product-block-price-new">1,500,000₫</span></div>
                                @endif
                                <div class="mt-2">
                                    @php $qty = rand(0, 50); @endphp
                                    @if($qty > 0)
                                        <span class="badge bg-success">Còn {{ $qty }} sản phẩm</span>
                                    @else
                                        <span class="badge bg-danger">Hết hàng</span>
                                    @endif
                                </div>
                            </div>
                            <a href="#" class="btn product-block-btn w-100">Đặt hàng ngay</a>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
            <div class="text-end mt-3">
                <a href="/products?category=Yến+Tinh+Chế" class="text-decoration-none text-primary fw-bold">
                    Xem tất cả <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </section>
    </section>

    <div class="container">
        <section class="mb-5">
            <div class="category-title-wrapper">
                <h2 class="category-title mb-0">Yến Chưng Sẵn</h2>
            </div>
            <div class="product-row">
                @for($i=1;$i<=4;$i++)
                <div class="product-col">
                    <div class="product-block-card">
                        <div class="product-block-image-wrapper">
                            <img src="https://placehold.co/600x400/e8e8e8/333333?text=San+pham+Chung+San" class="product-block-image" alt="Sản phẩm Chưng Sẵn">
                            <div class="product-block-cart-icon">
                                <i class="bi bi-cart-plus text-white fs-4"></i>
                            </div>
                        </div>
                        <div class="product-block-body">
                            <h5 class="product-block-title">Sản phẩm Chưng Sẵn #{{ $i }}</h5>
                            <div class="product-block-price-section text-end">
                                <div><span class="product-block-price-new">200,000₫</span></div>
                                <div class="mt-2">
                                    @php $qty = rand(0, 50); @endphp
                                    @if($qty > 0)
                                        <span class="badge bg-success">Còn {{ $qty }} sản phẩm</span>
                                    @else
                                        <span class="badge bg-danger">Hết hàng</span>
                                    @endif
                                </div>
                            </div>
                            <a href="#" class="btn product-block-btn w-100">Đặt hàng ngay</a>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
            <div class="text-end mt-3">
                <a href="/products?category=Yến+Chưng+Sẵn" class="text-decoration-none text-primary fw-bold">
                    Xem tất cả <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </section>
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
            <h4 class="text-center mb-4 fw-bold">Ý Kiến Khách Hàng</h4>
            <div id="customerReviewsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#customerReviewsCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
                    <button type="button" data-bs-target="#customerReviewsCarousel" data-bs-slide-to="1"></button>
                </div>
                <div class="carousel-inner">
                    <!-- Slide 1: First 3 reviews -->
                    <div class="carousel-item active">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="review-card text-center p-4 h-100">
                                    <div class="customer-avatar mx-auto mb-3">
                                        <img src="{{ asset('images/customers/customer1.jpg') }}" alt="Nguyễn Văn A" class="rounded-circle">
                                    </div>
                                    <h5 class="customer-name fw-bold mb-2">Nguyễn Văn A</h5>
                                    <div class="rating mb-3">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                    </div>
                                    <p class="review-text text-muted fst-italic">
                                        "Sản phẩm rất tốt, đóng gói kỹ lưỡng. Chất lượng yến sào tuyệt vời, gia đình tôi rất hài lòng. Sẽ tiếp tục ủng hộ!"
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="review-card text-center p-4 h-100">
                                    <div class="customer-avatar mx-auto mb-3">
                                        <img src="{{ asset('images/customers/customer2.jpg') }}" alt="Trần Thị B" class="rounded-circle">
                                    </div>
                                    <h5 class="customer-name fw-bold mb-2">Trần Thị B</h5>
                                    <div class="rating mb-3">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                    </div>
                                    <p class="review-text text-muted fst-italic">
                                        "Dịch vụ nhanh chóng, nhân viên tư vấn nhiệt tình. Giá cả hợp lý, chất lượng tốt. Rất đáng để mua!"
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="review-card text-center p-4 h-100">
                                    <div class="customer-avatar mx-auto mb-3">
                                        <img src="{{ asset('images/customers/customer3.jpg') }}" alt="Lê Minh C" class="rounded-circle">
                                    </div>
                                    <h5 class="customer-name fw-bold mb-2">Lê Minh C</h5>
                                    <div class="rating mb-3">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                    </div>
                                    <p class="review-text text-muted fst-italic">
                                        "Yến sào chất lượng cao, nguồn gốc rõ ràng. Đã mua nhiều lần và luôn hài lòng. Cảm ơn shop!"
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Slide 2: Next 3 reviews -->
                    <div class="carousel-item">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="review-card text-center p-4 h-100">
                                    <div class="customer-avatar mx-auto mb-3">
                                        <img src="{{ asset('images/customers/customer1.jpg') }}" alt="Phạm Văn D" class="rounded-circle">
                                    </div>
                                    <h5 class="customer-name fw-bold mb-2">Phạm Văn D</h5>
                                    <div class="rating mb-3">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                    </div>
                                    <p class="review-text text-muted fst-italic">
                                        "Shop giao hàng đúng hẹn, sản phẩm chất lượng. Rất hài lòng với dịch vụ!"
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="review-card text-center p-4 h-100">
                                    <div class="customer-avatar mx-auto mb-3">
                                        <img src="{{ asset('images/customers/customer2.jpg') }}" alt="Hoàng Thị E" class="rounded-circle">
                                    </div>
                                    <h5 class="customer-name fw-bold mb-2">Hoàng Thị E</h5>
                                    <div class="rating mb-3">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star text-warning"></i>
                                    </div>
                                    <p class="review-text text-muted fst-italic">
                                        "Yến sào ngon, bổ dưỡng. Gia đình tôi dùng rất thích. Sẽ giới thiệu cho bạn bè!"
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="review-card text-center p-4 h-100">
                                    <div class="customer-avatar mx-auto mb-3">
                                        <img src="{{ asset('images/customers/customer3.jpg') }}" alt="Vũ Minh F" class="rounded-circle">
                                    </div>
                                    <h5 class="customer-name fw-bold mb-2">Vũ Minh F</h5>
                                    <div class="rating mb-3">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <i class="bi bi-star-fill text-warning"></i>
                                    </div>
                                    <p class="review-text text-muted fst-italic">
                                        "Chất lượng tuyệt vời, giá cả phải chăng. Rất đáng để thử!"
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                            </p>
                        </div>
                    </div>
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

    <!-- Contact Form -->
    <!-- <div class="container">
        <section class="mb-5 row">
        <div class="col-md-12">
            <h4>Form tư vấn</h4>
            <form>
                <div class="mb-2">
                    <input class="form-control" placeholder="Họ và tên">
                </div>
                <div class="mb-2">
                    <input class="form-control" placeholder="SĐT">
                </div>
                <div class="mb-2">
                    <input class="form-control" placeholder="Email">
                </div>
                <div class="mb-2">
                    <textarea class="form-control" rows="3" placeholder="Nội dung"></textarea>
                </div>
                <button class="btn" style="background-color:#F5B041;color:#000">Gửi tư vấn</button>
            </form>
        </div> -->
    </section>

@endsection

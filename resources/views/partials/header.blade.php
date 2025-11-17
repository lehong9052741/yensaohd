<header id="mainHeader" class="fixed-top header-main">
    <!-- Top bar -->
    <div class="top-bar header-topbar">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between header-topbar-container">
                <!-- Logo - Always visible -->
                <div class="d-flex align-items-center header-logo-wrapper">
                    <a class="d-flex align-items-center text-dark text-decoration-none" href="/">
                        <img src="{{ asset('images/banners/logo.png') }}" 
                             alt="Yến Sào Hoàng Đăng" 
                             width="80" 
                             height="80"
                             loading="eager"
                             decoding="async"
                             class="header-logo">
                    
                        <div class="d-flex flex-column header-brand-text ms-2">
                            <div class="header-brand-name" style="font-size: 1rem; font-weight: bold;">YẾN SÀO HOÀNG ĐĂNG</div>
                        </div>
                    </a>
                </div>

                <!-- Desktop Search - Hidden on mobile -->
                <div class="flex-grow-1 px-3 d-none d-lg-block">
                    <form action="/products" method="get">
                        <div class="input-group">
                            <input name="search" class="form-control" placeholder="Tìm kiếm sản phẩm" aria-label="Tìm sản phẩm">
                            <button class="btn text-dark header-search-btn" type="submit">Tìm</button>
                        </div>
                    </form>
                </div>

                <!-- Right icons -->
                <div class="d-flex align-items-center gap-2 gap-md-3">
                    <!-- Mobile Search Toggle -->
                    <button class="btn btn-link text-white p-0 d-lg-none" id="mobileSearchToggle">
                        <span class="material-icons-outlined header-icon">search</span>
                    </button>

                    <!-- Phone (hidden on mobile) -->
                    <a href="tel:0900000000" class="text-white d-none d-md-flex align-items-center text-decoration-none">
                        <span class="material-icons-outlined text-white header-icon">phone</span>
                        <div class="d-none d-lg-block ms-2">
                            <div class="fw-medium">Hỗ Trợ Khách Hàng</div>
                            <div class="header-phone-text">0900 000 000</div>
                        </div>
                    </a>

                    <!-- Cart -->
                    <a class="text-white d-flex align-items-center text-decoration-none position-relative" href="/cart">
                        @php $cart = session('cart', []); $cartCount = count($cart); $total = array_reduce($cart, function($s,$i){return $s+($i['price']*$i['quantity']);},0); @endphp
                        <div class="position-relative">
                            <span class="material-icons-outlined text-white header-icon">shopping_cart</span>
                            @if($cartCount > 0)
                                <span id="cart-count-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-white fw-bold" style="font-size: 0.7rem;">{{ $cartCount }}</span>
                            @else
                                <span id="cart-count-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-white fw-bold d-none" style="font-size: 0.7rem;">0</span>
                            @endif
                        </div>
                        <div class="d-none d-lg-block ms-2">
                            <div class="fw-medium">Giỏ hàng</div>
                        </div>
                    </a>
                    
                    <!-- Mobile Menu Toggle -->
                    <button class="btn btn-link text-white p-0 d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                        <span class="material-icons-outlined header-icon">menu</span>
                    </button>

                    @auth
                        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-warning text-white d-none d-lg-inline">Quản trị</a>
                    @endauth

                    <!-- Login (desktop only) -->
                    @guest
                        <a class="text-white d-none d-lg-flex align-items-center text-decoration-none" href="{{ route('login') }}">
                            <span class="material-icons-outlined text-white header-icon">login</span>
                            <div class="ms-2">
                                <div class="fw-medium">Đăng nhập</div>
                            </div>
                        </a>
                    @endguest
                </div>
            </div>

            <!-- Mobile Search Bar (toggleable) -->
            <div class="mobile-search-bar d-lg-none" id="mobileSearchBar" style="display: none;">
                <form action="/products" method="get" class="py-2">
                    <div class="input-group">
                        <input name="search" class="form-control" placeholder="Tìm kiếm sản phẩm">
                        <button class="btn btn-light" type="submit">
                            <span class="material-icons-outlined" style="font-size: 20px;">search</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Desktop Navigation -->
    <nav class="navbar navbar-expand-lg border-bottom header-nav d-none d-lg-block">
        <div class="container">
            <div class="d-flex align-items-center w-100">
                <!-- Categories dropdown -->
                <div class="dropdown me-3">
                    <button class="btn btn-outline-secondary d-flex align-items-center" id="categoriesBtn" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-list"></i>
                        <span class="ms-2 fw-semibold">Danh mục sản phẩm</span>
                    </button>
                    <ul class="dropdown-menu p-0 shadow border-0 header-categories-dropdown" aria-labelledby="categoriesBtn">
                        @foreach($globalCategories ?? [] as $category)
                        <li>
                            <a class="menu-item" href="/products?category={{ urlencode($category) }}">
                                <i class="bi bi-box-seam"></i>
                                <span>{{ $category }}</span>
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <ul class="navbar-nav ms-auto mb-0">
                    <li class="nav-item"><a class="nav-link" href="/">Trang Chủ</a></li>
                    <li class="nav-item">
                        <a class="nav-link promotion-link" href="/promotions">
                            <i class="bi bi-fire text-danger me-1"></i>Khuyến Mãi
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/news">Tin Tức</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about">Giới Thiệu</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contact">Liên Hệ</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<!-- Mobile Offcanvas Menu -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <ul class="list-unstyled mobile-menu">
            <li><a class="mobile-menu-item" href="/"><i class="bi bi-house-door me-2"></i>Trang Chủ</a></li>
            
            <!-- Categories submenu -->
            <li>
                <a class="mobile-menu-item" data-bs-toggle="collapse" href="#categoriesCollapse">
                    <i class="bi bi-box-seam me-2"></i>Danh mục sản phẩm
                    <i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="categoriesCollapse">
                    <ul class="list-unstyled ps-4">
                        @foreach($globalCategories ?? [] as $category)
                        <li><a class="mobile-submenu-item" href="/products?category={{ urlencode($category) }}">{{ $category }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </li>
            
            <li><a class="mobile-menu-item" href="/promotions"><i class="bi bi-fire text-danger me-2"></i>Khuyến Mãi</a></li>
            <li><a class="mobile-menu-item" href="/news"><i class="bi bi-newspaper me-2"></i>Tin Tức</a></li>
            <li><a class="mobile-menu-item" href="/about"><i class="bi bi-info-circle me-2"></i>Giới Thiệu</a></li>
            <li><a class="mobile-menu-item" href="/contact"><i class="bi bi-telephone me-2"></i>Liên Hệ</a></li>
            
            @guest
            <li><a class="mobile-menu-item" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập</a></li>
            @endguest
            
            @auth
            <li><a class="mobile-menu-item" href="{{ route('admin.products.index') }}"><i class="bi bi-gear me-2"></i>Quản trị</a></li>
            @endauth
        </ul>
    </div>
</div>

<div id="cart-dropdown" class="d-none">
    @include('partials.cart-dropdown', ['cart' => $cart, 'cartCount' => $cartCount, 'total' => $total])
</div>

<script>
// Mobile search toggle
document.getElementById('mobileSearchToggle')?.addEventListener('click', function() {
    const searchBar = document.getElementById('mobileSearchBar');
    if (searchBar.style.display === 'none' || !searchBar.style.display) {
        searchBar.style.display = 'block';
        searchBar.querySelector('input').focus();
    } else {
        searchBar.style.display = 'none';
    }
});
</script>

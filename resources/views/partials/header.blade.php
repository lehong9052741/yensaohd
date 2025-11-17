<header id="mainHeader" class="fixed-top header-main">
    <!-- Top bar -->
    <!-- <div class="top-bar" style="background-color:#ffffff; color:#000;"> -->
        <div class="top-bar header-topbar">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between header-topbar-container">
                <!-- Logo -->
                <div class="d-flex align-items-center">
                    <a class="d-flex align-items-center text-dark text-decoration-none" href="/">
                        <img src="{{ asset('images/banners/logo.png') }}" 
                             alt="Yến Sào Hoàng Đăng" 
                             width="100" 
                             height="100"
                             loading="eager"
                             decoding="async"
                             class="d-none d-sm-block me-3 header-logo">
                    
                    <div class="d-none d-lg-flex flex-column header-brand-text">
                        <div class="header-brand-name">YẾN SÀO HOÀNG ĐĂNG</div>
                        <div class="header-brand-tagline">
                            <span class="tagline-yellow">Chất lượng vàng - Giá yêu thương</span>
                        </div>
                    </div>
                    </a>
                </div>
                <!-- Categories dropdown (visible when scrolled) -->
                <div class="dropdown header-scrolled-categories d-none">
                    <button class="btn btn-outline-light d-flex align-items-center" id="categoriesBtnScrolled" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-list"></i>
                        <span class="ms-2 fw-semibold d-none d-md-inline">Danh mục sản phẩm</span>
                    </button>
                    <ul class="dropdown-menu p-0 shadow border-0 header-categories-dropdown" aria-labelledby="categoriesBtnScrolled">
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

                <!-- Search -->
                <div class="flex-grow-1 px-3 d-none d-md-block">
                    <form action="/products" method="get">
                        <div class="input-group">
                            <input name="search" class="form-control" placeholder="Tìm kiếm sản phẩm" aria-label="Tìm sản phẩm">
                            <button class="btn text-dark header-search-btn" type="submit">Tìm</button>
                        </div>
                    </form>
                </div>

                <!-- Right icons -->
                <div class="d-flex align-items-center gap-3">
                    <a href="tel:0900000000" class="text-white d-flex align-items-center text-decoration-none">
                        <span class="material-icons-outlined text-white header-icon">phone</span>
                        <div class="d-none d-md-block ms-2">
                            <div class="fw-medium">Hỗ Trợ Khách Hàng</div>
                            <div class="header-phone-text">0900 000 000</div>
                        </div>
                    </a>
                    <!-- Cart -->
                    <div class="ms-2 dropdown">
                        @php $cart = session('cart', []); $cartCount = count($cart); $total = array_reduce($cart, function($s,$i){return $s+($i['price']*$i['quantity']);},0); @endphp
                        <a class="text-white d-flex align-items-center text-decoration-none position-relative" href="/cart">
                            <div class="position-relative">
                                <span class="material-icons-outlined text-white header-icon">shopping_cart</span>
                                @if($cartCount > 0)
                                    <span id="cart-count-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-white fw-bold" style="font-size: 0.7rem;">{{ $cartCount }}</span>
                                @else
                                    <span id="cart-count-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-white fw-bold d-none" style="font-size: 0.7rem;">0</span>
                                @endif
                            </div>
                            <div class="d-none d-md-block ms-2">
                                <div class="fw-medium">Giỏ hàng</div>
                            </div>
                        </a>
                        <div id="cart-dropdown" class="dropdown-menu dropdown-menu-end p-0 shadow header-cart-dropdown">
                            @include('partials.cart-dropdown', ['cart' => $cart, 'cartCount' => $cartCount, 'total' => $total])
                        </div>
                    </div>
                    
                    @auth
                        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-warning text-white d-none d-lg-inline">Quản trị</a>
                    @endauth

                    <!-- Login / User -->
                    @auth
                        <div class="d-none d-md-flex align-items-center">
                            <span class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center header-user-avatar">{{ substr(Auth::user()->name,0,1) }}</span>
                            <a href="#" class="ms-2 text-white text-decoration-none">{{ Auth::user()->name }}</a>
                        </div>
                    @else
                        <a class="text-white d-flex align-items-center text-decoration-none" href="{{ route('login') }}">
                            <span class="material-icons-outlined text-white header-icon">login</span>
                            <div class="d-none d-md-block ms-2">
                                <div class="fw-medium">Đăng nhập</div>
                            </div>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg border-bottom header-nav">
    <div class="container">
      <div class="d-flex align-items-center gap-2">
        <!-- Categories dropdown (hamburger) -->
        <div class="dropdown">
          <button class="btn btn-outline-secondary d-flex align-items-center" id="categoriesBtn" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-list"></i>
            <span class="ms-2 fw-semibold">Danh mục sản phẩm</span>
          </button>

          <!-- Custom styled dropdown -->
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

        <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavRow">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>

      <div class="collapse navbar-collapse" id="mainNavRow">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="/">Trang Chủ</a></li>
          <!-- <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navProducts" data-bs-toggle="dropdown">Sản Phẩm</a>
            <ul class="dropdown-menu p-0 shadow border-0 header-product-dropdown" aria-labelledby="navProducts">
              <li><a class="menu-item" href="/products?category=Yến+Thô"><i class="bi bi-egg"></i><span>Yến Thô</span></a></li>
              <li><a class="menu-item" href="/products?category=Yến+Tinh+Chế"><i class="bi bi-droplet"></i><span>Yến Tinh Chế</span></i></a></li>
              <li><a class="menu-item" href="/products?category=Yến+Chưng+Sẵn"><i class="bi bi-jar"></i><span>Yến Chưng Sẵn</span></a></li>
            </ul>
          </li> -->
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

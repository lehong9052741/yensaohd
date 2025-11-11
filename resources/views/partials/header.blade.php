<header id="mainHeader" class="fixed-top" style="z-index:1030;">
    <!-- Top bar -->
    <!-- <div class="top-bar" style="background-color:#ffffff; color:#000;"> -->
        <div class="top-bar" style="background-color: #1e3d37; color:#FFFFFF;">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between" style="min-height:72px;">
                <!-- Logo -->
                <a class="d-flex align-items-center text-dark text-decoration-none" href="/">
                    <img src="{{ asset('storage/banners/logo.png') }}" alt="Logo" height="150" width="150" class="d-none d-sm-block me-3">
                    <!-- <div>
                        <div class="fw-bold align-items-center" style="font-size:1.25rem;">YẾN SÀO<br/> HOÀNG ĐĂNG</div>
                        <div class="small text-dark">Tinh hoa từ thiên nhiên</div>
                    </div> -->
                </a>

                <!-- Search -->
                <div class="flex-grow-1 px-3 d-none d-md-block">
                    <form action="/products" method="get">
                        <div class="input-group">
                            <input name="search" class="form-control" placeholder="Tìm sản phẩm, ví dụ: yến thô" aria-label="Tìm sản phẩm">
                            <button class="btn text-dark" type="submit" style="background-color: #FFF4C1; border: 1px solid rgba(0,0,0,0.08);">Tìm</button>
                        </div>
                    </form>
                </div>

                <!-- Right icons -->
                <div class="d-flex align-items-center gap-3">
                    <a href="tel:0900000000" class="text-white d-flex align-items-center text-decoration-none">
                        <span class="material-icons-outlined text-white" style="font-size: 2rem;">phone</span>
                        <div class="d-none d-md-block ms-2">
                            <div class="fw-medium">Hỗ Trợ Khách Hàng</div>
                            <div style="font-size: 0.9rem;">0900 000 000</div>
                        </div>
                    </a>
                    <!-- Cart -->
                    <div class="ms-2 dropdown">
                        @php $cart = session('cart', []); $cartCount = count($cart); $total = array_reduce($cart, function($s,$i){return $s+($i['price']*$i['quantity']);},0); @endphp
                        <a class="text-white d-flex align-items-center text-decoration-none position-relative" href="/cart" data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <span class="material-icons-outlined text-white" style="font-size: 2rem;">shopping_cart</span>
                            <div class="d-none d-md-block ms-2">
                                <div class="fw-medium">Giỏ hàng</div>
                            </div>
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark">{{ $cartCount }}</span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end p-0 shadow" style="width:320px;">
                            <div class="p-3 brand-light border-bottom"><strong>Giỏ hàng ({{ $cartCount }})</strong></div>
                            <div style="max-height:300px; overflow:auto;" class="p-2">
                                @forelse($cart as $id => $item)
                                    <div class="d-flex gap-2 align-items-center py-2 border-bottom">
                                        <img src="{{ asset('storage/' . ($item['image'] ?? '')) }}" alt="" class="rounded" style="width:48px;height:48px;object-fit:cover">
                                        <div class="flex-grow-1">
                                            <div class="fw-medium">{{ $item['name'] }}</div>
                                            <div class="small text-muted">{{ number_format($item['price'],0,',','.') }}₫ × {{ $item['quantity'] }}</div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-muted">
                                        <span class="material-icons-outlined text-white" style="font-size:3rem">shopping_cart</span>
                                        <p class="mt-2 mb-0">Giỏ hàng trống</p>
                                    </div>
                                @endforelse
                            </div>
                            @if($cartCount > 0)
                                <div class="p-3 brand-light border-top">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong>Tổng:</strong>
                                        <strong>{{ number_format($total,0,',','.') }}₫</strong>
                                    </div>
                                    <a href="/cart" class="btn btn-warning w-100 text-warning">Xem giỏ hàng</a>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @auth
                        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-warning text-white d-none d-lg-inline">Quản trị</a>
                    @endauth

                    <!-- Login / User -->
                    @auth
                        <div class="d-none d-md-flex align-items-center">
                            <span class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width:34px;height:34px">{{ substr(Auth::user()->name,0,1) }}</span>
                            <a href="#" class="ms-2 text-white text-decoration-none">{{ Auth::user()->name }}</a>
                        </div>
                    @else
                        <a class="text-white d-flex align-items-center text-decoration-none" href="{{ route('login') }}">
                            <span class="material-icons-outlined text-white" style="font-size: 2rem;">login</span>
                            <div class="d-none d-md-block ms-2">
                                <div class="fw-medium">Đăng nhập</div>
                            </div>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- <nav class="navbar navbar-expand-lg border-bottom" style="background-color:#ffffff;">
        <div class="container">
            <div class="d-flex align-items-center gap-2">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary d-flex align-items-center" id="categoriesBtn" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="material-icons">menu</span>
                        <span class="ms-2">Danh mục sản phẩm</span>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="categoriesBtn">
                        <li><a class="dropdown-item" href="/products?category=Yến+Thô">Yến Thô</a></li>
                        <li><a class="dropdown-item" href="/products?category=Yến+Tinh+Chế">Yến Tinh Chế</a></li>
                        <li><a class="dropdown-item" href="/products?category=Yến+Chưng+Sẵn">Yến Chưng Sẵn</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/products">Tất cả sản phẩm</a></li>
                    </ul>
                </div>

                <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavRow">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="mainNavRow">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="/">Trang Chủ</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navProducts" data-bs-toggle="dropdown">Sản Phẩm</a>
                        <ul class="dropdown-menu" aria-labelledby="navProducts">
                            <li><a class="dropdown-item" href="/products?category=Yến+Thô">Yến Thô</a></li>
                            <li><a class="dropdown-item" href="/products?category=Yến+Tinh+Chế">Yến Tinh Chế</a></li>
                            <li><a class="dropdown-item" href="/products?category=Yến+Chưng+Sẵn">Yến Chưng Sẵn</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/promotions">Khuyến Mãi</a></li>
                    <li class="nav-item"><a class="nav-link" href="/news">Tin Tức</a></li>
                    <li class="nav-item"><a class="nav-link" href="/about">Giới Thiệu</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contact">Liên Hệ</a></li>
                </ul>
            </div>
        </div>
    </nav> -->

    <nav class="navbar navbar-expand-lg border-bottom" style="background-color:#ffffff;">
    <div class="container">
      <div class="d-flex align-items-center gap-2">
        <!-- Categories dropdown (hamburger) -->
        <div class="dropdown">
          <button class="btn btn-outline-secondary d-flex align-items-center" id="categoriesBtn" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-list"></i>
            <span class="ms-2 fw-semibold">Danh mục sản phẩm</span>
          </button>

          <!-- Custom styled dropdown -->
          <ul class="dropdown-menu p-0 shadow border-0" aria-labelledby="categoriesBtn" style="width:260px; border-radius:10px;">
            <li><a class="menu-item" href="#"><i class="bi bi-egg"></i><span>Tổ Yến</span><i class="bi bi-chevron-right"></i></a></li>
            <li><a class="menu-item" href="#"><i class="bi bi-droplet"></i><span>Yến Chưng Tươi</span><i class="bi bi-chevron-right"></i></a></li>
            <li><a class="menu-item" href="#"><i class="bi bi-jar"></i><span>Yến Chưng Sẵn</span><i class="bi bi-chevron-right"></i></a></li>
            <li><a class="menu-item" href="#"><i class="bi bi-flower1"></i><span>Cici Skin Aura</span><i class="bi bi-chevron-right"></i></a></li>
            <li><a class="menu-item" href="#"><i class="bi bi-gift"></i><span>Set Quà Biếu Cao Cấp</span><i class="bi bi-chevron-right"></i></a></li>
            <li><a class="menu-item" href="#"><i class="bi bi-cake2"></i><span>Set Bánh Trung Thu</span><i class="bi bi-chevron-right"></i></a></li>
            <li><a class="menu-item" href="#"><i class="bi bi-cup-hot"></i><span>Soup Bóng Cá</span><i class="bi bi-chevron-right"></i></a></li>
            <li><a class="menu-item" href="#"><i class="bi bi-shop"></i><span>Fer Valley</span><i class="bi bi-chevron-right"></i></a></li>
          </ul>
        </div>

        <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavRow">
          <span class="navbar-toggler-icon"></span>
        </button>
      </div>

      <div class="collapse navbar-collapse" id="mainNavRow">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="/">Trang Chủ</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navProducts" data-bs-toggle="dropdown">Sản Phẩm</a>
            <ul class="dropdown-menu" aria-labelledby="navProducts">
              <li><a class="dropdown-item" href="#">Yến Thô</a></li>
              <li><a class="dropdown-item" href="#">Yến Tinh Chế</a></li>
              <li><a class="dropdown-item" href="#">Yến Chưng Sẵn</a></li>
            </ul>
          </li>
          <li class="nav-item"><a class="nav-link" href="/promotions">Khuyến Mãi</a></li>
          <li class="nav-item"><a class="nav-link" href="/news">Tin Tức</a></li>
          <li class="nav-item"><a class="nav-link" href="/about">Giới Thiệu</a></li>
          <li class="nav-item"><a class="nav-link" href="/contact">Liên Hệ</a></li>
        </ul>
      </div>
    </div>
  </nav>
</header>

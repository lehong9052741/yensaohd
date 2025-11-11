@extends('layouts.master')

@section('content')
    <!-- Banner / Carousel -->
    <!-- <div class="container-fluid px-0"> -->
    <div class="container">
        <div id="homeCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('storage/banners/slide1.jpg') }}" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h3 class="display-4 fw-bold">Tinh Hoa Từ Tổ Yến</h3>
                        <p class="lead">Chất lượng từ tâm</p>
                    </div>
                </div>
            <div class="carousel-item">
                <img src="{{ asset('storage/banners/slide2.jpg') }}" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('storage/banners/slide3.jpg') }}" class="d-block w-100" alt="...">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Product Blocks -->
    <div class="container">
        <section class="mb-5">
            <h2 class="mb-3">Yến Thô Tự Nhiên</h2>
            <p class="text-muted">Tổ yến nguyên chất, giữ trọn dưỡng chất tự nhiên.</p>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @for($i=1;$i<=3;$i++)
            <div class="col">
                <div class="card h-100">
                    <img src="https://via.placeholder.com/600x400?text=San+pham+Thô+" class="card-img-top" alt="...">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Sản phẩm Thô #{{ $i }}</h5>
                        <p class="card-text text-muted">Giá: 1,000,000₫</p>
                        <div class="mt-auto">
                            <a href="#" class="btn btn-warning text-dark">Thêm vào giỏ hàng</a>
                        </div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </section>
    </div>

    <section class="mb-5 p-3" style="background-color:#FFF4C1">
        <div class="container">
            <h2 class="mb-3">Yến Tinh Chế</h2>
            <p class="text-muted">Tinh chọn từng sợi yến, sạch lông, sẵn sàng cho bữa bổ dưỡng.</p>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @for($i=1;$i<=3;$i++)
            <div class="col">
                <div class="card h-100">
                    <img src="https://via.placeholder.com/600x400?text=San+pham+Tinh+Che" class="card-img-top" alt="...">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Sản phẩm Tinh Chế #{{ $i }}</h5>
                        <p class="card-text text-muted">Giá: 1,500,000₫</p>
                        <div class="mt-auto">
                            <a href="#" class="btn btn-warning text-dark">Thêm vào giỏ hàng</a>
                        </div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </section>
    </div>

    <div class="container">
        <section class="mb-5">
            <h2 class="mb-3">Yến Chưng Sẵn</h2>
            <p class="text-muted">Yến chưng sẵn tiện lợi – bổ dưỡng mỗi ngày.</p>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @for($i=1;$i<=3;$i++)
            <div class="col">
                <div class="card h-100 rounded">
                    <img src="https://via.placeholder.com/600x400?text=San+pham+Chung+San" class="card-img-top" alt="...">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Sản phẩm Chưng Sẵn #{{ $i }}</h5>
                        <p class="card-text text-muted">Giá: 200,000₫</p>
                        <div class="mt-auto">
                            <a href="#" class="btn btn-outline-secondary">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </section>
    </div>

    <!-- Customer reviews & contact form (placeholder) -->
    <div class="container">
        <section class="mb-5 row">
            <div class="col-md-7">
            <h4>Ý kiến khách hàng</h4>
            <div class="bg-light p-3">
                <p><strong>Nguyễn A:</strong> Sản phẩm rất tốt, đóng gói kỹ.</p>
                <p><strong>Trần B:</strong> Dịch vụ nhanh, nhân viên thân thiện.</p>
            </div>
        </div>
        <div class="col-md-5">
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
        </div>
    </section>

@endsection

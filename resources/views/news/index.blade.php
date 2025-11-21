@extends('layouts.master')

@section('title', 'Cẩm Nang & Tin Tức - Yến Sào Hoàng Đăng')

@section('content')
<div class="container my-5">
    <!-- Page Header -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-3" style="color: #d4af37;">CẨM NANG & TIN TỨC</h1>
        <div class="mx-auto" style="width: 120px; height: 4px; background: #d4af37; border-radius: 2px;"></div>
        <p class="text-muted mt-3">Chia sẻ kiến thức và kinh nghiệm về yến sào</p>
    </div>

    <!-- News Grid -->
    <div class="row g-4 mb-5">
        @foreach($news as $item)
        <div class="col-md-4">
            <article class="news-card h-100">
                <a href="{{ url('/news/' . $item['slug']) }}" class="text-decoration-none">
                    <div class="news-image-wrapper">
                        <img src="{{ $item['image'] }}" 
                             alt="{{ $item['title'] }}" 
                             class="news-image"
                             onerror="this.src='/images/error/error-404.png'">
                        <div class="news-category-badge">{{ $item['category'] }}</div>
                    </div>
                    <div class="news-body">
                        <h3 class="news-title">{{ $item['title'] }}</h3>
                        <p class="news-excerpt">{{ $item['excerpt'] }}</p>
                        <div class="news-meta">
                            <span class="news-date">
                                <i class="bi bi-calendar3"></i>
                                {{ date('d/m/Y', strtotime($item['published_at'])) }}
                            </span>
                            <span class="news-views">
                                <i class="bi bi-eye"></i>
                                {{ number_format($item['views']) }} lượt xem
                            </span>
                        </div>
                    </div>
                </a>
            </article>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($lastPage > 1)
    <nav aria-label="News pagination">
        <ul class="pagination justify-content-center">
            <!-- Previous -->
            @if($page > 1)
            <li class="page-item">
                <a class="page-link" href="?page={{ $page - 1 }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            @endif

            <!-- Page Numbers -->
            @for($i = 1; $i <= $lastPage; $i++)
            <li class="page-item {{ $i == $page ? 'active' : '' }}">
                <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
            </li>
            @endfor

            <!-- Next -->
            @if($page < $lastPage)
            <li class="page-item">
                <a class="page-link" href="?page={{ $page + 1 }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
            @endif
        </ul>
    </nav>
    @endif
</div>
@endsection

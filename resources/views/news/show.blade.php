@extends('layouts.master')

@section('title', $article['title'] . ' - Yến Sào Hoàng Đăng')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <article class="news-detail bg-white rounded shadow-sm p-4">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/news') }}">Tin tức</a></li>
                        <li class="breadcrumb-item active">{{ $article['title'] }}</li>
                    </ol>
                </nav>

                <!-- Article Header -->
                <header class="mb-4">
                    <div class="mb-3">
                        <span class="badge bg-warning text-dark">{{ $article['category'] }}</span>
                    </div>
                    <h1 class="article-title">{{ $article['title'] }}</h1>
                    <div class="article-meta">
                        <span class="me-3">
                            <i class="bi bi-person-circle"></i>
                            {{ $article['author'] }}
                        </span>
                        <span class="me-3">
                            <i class="bi bi-calendar3"></i>
                            {{ date('d/m/Y', strtotime($article['published_at'])) }}
                        </span>
                        <span>
                            <i class="bi bi-eye"></i>
                            {{ number_format($article['views']) }} lượt xem
                        </span>
                    </div>
                </header>

                <!-- Featured Image -->
                <div class="article-image mb-4">
                    <img src="{{ $article['image'] }}" 
                         alt="{{ $article['title'] }}" 
                         class="img-fluid rounded"
                         onerror="this.src='/images/error/error-404.png'">
                </div>

                <!-- Article Content -->
                <div class="article-content">
                    <div class="article-excerpt">
                        <strong>{{ $article['excerpt'] }}</strong>
                    </div>
                    <div class="article-body">
                        {!! nl2br($article['content']) !!}
                    </div>
                </div>

                <!-- Share Buttons -->
                <div class="article-share mt-5">
                    <h5 class="mb-3">Chia sẻ bài viết:</h5>
                    <div class="d-flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" 
                           target="_blank" 
                           class="btn btn-primary btn-sm">
                            <i class="bi bi-facebook"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ $article['title'] }}" 
                           target="_blank" 
                           class="btn btn-info btn-sm text-white">
                            <i class="bi bi-twitter"></i> Twitter
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ url()->current() }}" 
                           target="_blank" 
                           class="btn btn-secondary btn-sm">
                            <i class="bi bi-linkedin"></i> LinkedIn
                        </a>
                    </div>
                </div>
            </article>

            <!-- Related Articles -->
            @if(count($relatedNews) > 0)
            <section class="related-articles mt-5">
                <h3 class="mb-4">Bài viết liên quan</h3>
                <div class="row g-4">
                    @foreach($relatedNews as $related)
                    <div class="col-md-4">
                        <article class="related-card">
                            <a href="{{ url('/news/' . $related['slug']) }}" class="text-decoration-none">
                                <div class="related-image-wrapper">
                                    <img src="{{ $related['image'] }}" 
                                         alt="{{ $related['title'] }}"
                                         class="related-image"
                                         onerror="this.src='/images/error/error-404.png'">
                                </div>
                                <div class="related-body">
                                    <h5 class="related-title">{{ $related['title'] }}</h5>
                                    <span class="related-date">
                                        <i class="bi bi-calendar3"></i>
                                        {{ date('d/m/Y', strtotime($related['published_at'])) }}
                                    </span>
                                </div>
                            </a>
                        </article>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <aside class="sidebar">
                <!-- Popular Posts -->
                <div class="sidebar-widget mb-4">
                    <h4 class="widget-title">Bài viết phổ biến</h4>
                    <div class="popular-posts">
                        @php
                        $newsPath = public_path('data/news.json');
                        $allNews = json_decode(file_get_contents($newsPath), true);
                        usort($allNews, function($a, $b) {
                            return $b['views'] - $a['views'];
                        });
                        $popularNews = array_slice($allNews, 0, 5);
                        @endphp
                        
                        @foreach($popularNews as $index => $popular)
                        <div class="popular-post">
                            <span class="post-number">{{ $index + 1 }}</span>
                            <div class="post-content">
                                <a href="{{ url('/news/' . $popular['slug']) }}" class="post-link">
                                    {{ $popular['title'] }}
                                </a>
                                <span class="post-views">{{ number_format($popular['views']) }} lượt xem</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Categories -->
                <div class="sidebar-widget mb-4">
                    <h4 class="widget-title">Danh mục</h4>
                    <ul class="category-list">
                        @php
                        $categories = array_unique(array_column($allNews, 'category'));
                        @endphp
                        @foreach($categories as $cat)
                        <li>
                            <a href="{{ url('/news?category=' . urlencode($cat)) }}">
                                <i class="bi bi-folder"></i> {{ $cat }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Newsletter -->
                <div class="sidebar-widget newsletter-widget">
                    <h4 class="widget-title">Đăng ký nhận tin</h4>
                    <p>Nhận thông tin mới nhất về sản phẩm và khuyến mãi</p>
                    <form>
                        <input type="email" class="form-control mb-2" placeholder="Email của bạn" required>
                        <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection

@extends('layouts.master')

@section('content')
<div class="container">
    <h1 class="mb-4">Sản phẩm</h1>

    @if(request('category'))
        <p class="text-muted">Danh mục: {{ request('category') }}</p>
    @endif

    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach($products as $product)
            <div class="col">
                <div class="card h-100">
                    <img src="{{ $product->image ?? 'https://via.placeholder.com/600x400?text=No+Image' }}" class="card-img-top" alt="">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted">{{ number_format($product->price,0,',','.') }}₫</p>
                        <p class="card-text">{{ \Illuminate\Support\Str::limit($product->description ?? '', 100) }}</p>
                        <div class="mt-auto d-flex gap-2">
                            <a href="/products/{{ $product->id }}" class="btn btn-outline-secondary">Xem chi tiết</a>
                            <form action="/cart/add/{{ $product->id }}" method="post">
                                @csrf
                                <button class="btn btn-warning text-dark">Thêm vào giỏ hàng</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $products->withQueryString()->links() }}
    </div>
</div>
@endsection

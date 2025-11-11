@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ $product->image ?? 'https://via.placeholder.com/800x600?text=No+Image' }}" class="img-fluid" alt="">
        </div>
        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <p class="text-muted">{{ number_format($product->price,0,',','.') }}₫</p>
            <p>{{ $product->description }}</p>

            <form action="/cart/add/{{ $product->id }}" method="post" class="d-flex gap-2">
                @csrf
                <input type="number" name="quantity" value="1" min="1" class="form-control w-25">
                <button class="btn btn-warning text-dark">Thêm vào giỏ hàng</button>
            </form>
        </div>
    </div>
</div>
@endsection

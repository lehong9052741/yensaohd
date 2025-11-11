@extends('layouts.master')

@section('content')
<div class="container">
    <h1>Chỉnh sửa sản phẩm</h1>

    <form action="{{ route('admin.products.update', $product) }}" method="post" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.products._form')
    </form>
</div>
@endsection

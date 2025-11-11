@extends('layouts.master')

@section('content')
<div class="container">
    <h1>Thêm sản phẩm</h1>

    <form action="{{ route('admin.products.store') }}" method="post" enctype="multipart/form-data">
        @include('admin.products._form')
    </form>
</div>
@endsection

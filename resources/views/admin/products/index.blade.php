@extends('layouts.master')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Quản trị - Sản phẩm</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-success">Thêm sản phẩm</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Ảnh</th>
                <th>Tên</th>
                <th>Danh mục</th>
                <th>Giá</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td style="width:120px">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="" class="img-fluid">
                        @else
                            <div class="text-muted">No image</div>
                        @endif
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category }}</td>
                    <td>{{ number_format($product->price,0,',','.') }}₫</td>
                    <td>
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary">Sửa</a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="post" class="d-inline-block" onsubmit="return confirm('Xác nhận xóa?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div>
        {{ $products->links() }}
    </div>
</div>
@endsection

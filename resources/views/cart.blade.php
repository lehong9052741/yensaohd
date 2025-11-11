@extends('layouts.master')

@section('content')
<div class="container">
    <h1 class="mb-4">Giỏ hàng</h1>

    @php $cart = session('cart', []); @endphp

    @if(empty($cart))
        <div class="alert alert-info">Giỏ hàng trống.</div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Tổng</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach($cart as $id => $item)
                    @php $line = $item['price'] * $item['quantity']; $total += $line; @endphp
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ number_format($item['price'],0,',','.') }}₫</td>
                            <td>
                                <form action="/cart/update/{{ $id }}" method="post" class="d-flex gap-2 align-items-center">
                                    @csrf
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control form-control-sm" style="width:80px">
                                    <button class="btn btn-sm btn-outline-primary">Cập nhật</button>
                                </form>
                            </td>
                            <td>{{ number_format($line,0,',','.') }}₫</td>
                            <td>
                                <form action="/cart/remove/{{ $id }}" method="post">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center">
            <h4>Tổng: {{ number_format($total,0,',','.') }}₫</h4>
            <div class="d-flex gap-2">
                <form action="/cart/clear" method="post">
                    @csrf
                    <button class="btn btn-outline-secondary">Xóa giỏ hàng</button>
                </form>
                <a href="#" class="btn btn-primary">Thanh toán</a>
            </div>
        </div>
    @endif
</div>
@endsection

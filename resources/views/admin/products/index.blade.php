@extends('layouts.master')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-box-seam"></i> Quản lý Sản phẩm</h1>
        <div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-info">
                <i class="bi bi-receipt"></i> Quản lý Đơn hàng
            </a>
            <a href="{{ route('admin.products.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Thêm sản phẩm
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 80px">Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Sale</th>
                            <th>Tồn kho</th>
                            <th>Đã bán</th>
                            <th style="width: 180px">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    @if($product->is_best_seller)
                                        <br><span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> Best Seller</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->category)
                                        <span class="badge bg-info">{{ $product->category }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->has_sale)
                                        <div>
                                            <del class="text-muted small">{{ number_format($product->original_price ?? $product->price,0,',','.') }}₫</del>
                                            <br><strong class="text-danger">{{ number_format($product->display_price,0,',','.') }}₫</strong>
                                        </div>
                                    @else
                                        <strong>{{ number_format($product->price,0,',','.') }}₫</strong>
                                    @endif
                                </td>
                                <td>
                                    @if($product->discount_percent > 0)
                                        <span class="badge bg-danger">-{{ $product->discount_percent }}%</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($product->quantity <= 0)
                                        <span class="badge bg-danger">Hết hàng</span>
                                    @elseif($product->quantity <= 10)
                                        <span class="badge bg-warning text-dark">{{ $product->quantity }}</span>
                                    @else
                                        <span class="badge bg-success">{{ $product->quantity }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted">{{ $product->sold_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-pencil"></i> Sửa
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="post" class="d-inline-block" onsubmit="return confirm('Xác nhận xóa sản phẩm này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i> Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                    <p class="text-muted mt-2">Chưa có sản phẩm nào</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
</div>
@endsection

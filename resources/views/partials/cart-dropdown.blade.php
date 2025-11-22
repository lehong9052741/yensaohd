<div class="p-3 border-bottom" style="background-color: white;"><strong style="color: #000;">Giỏ hàng (<span id="dropdown-cart-count">{{ $cartCount }}</span>)</strong></div>
<div class="header-cart-content p-2" id="dropdown-cart-items" style="background-color: white;">
    @forelse($cart as $id => $item)
        <div class="d-flex gap-2 align-items-center py-2 border-bottom position-relative cart-item" data-item-id="{{ $id }}" data-price="{{ $item['price'] }}" data-quantity="{{ $item['quantity'] }}">
            <div class="flex-grow-1">
                <div class="fw-medium" style="color: #000;">{{ $item['name'] }}</div>
                <div class="small" style="color: #666;">{{ number_format($item['price'],0,',','.') }}₫ × {{ $item['quantity'] }}</div>
            </div>
            <form action="/cart/remove/{{ $id }}" method="POST" class="d-inline remove-cart-item-form">
                @csrf
                <button type="submit" class="btn btn-sm btn-link p-0 remove-cart-btn">
                    <i class="bi bi-trash" style="font-size: 1.2rem; color: #dc3545;"></i>
                </button>
            </form>
        </div>
    @empty
        <div class="text-center py-4" id="empty-cart-message" style="color: #666;">
            <i class="bi bi-cart" style="font-size: 3rem; color: #999;"></i>
            <p class="mt-2 mb-0">Giỏ hàng trống</p>
        </div>
    @endforelse
</div>
@if($cartCount > 0)
    <div class="p-3 border-top" id="dropdown-cart-footer" style="background-color: white;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <strong style="color: #000;">Tổng:</strong>
            <strong id="dropdown-cart-total" style="color: #000;">{{ number_format($total,0,',','.') }}₫</strong>
        </div>
        <a href="/cart" class="btn btn-warning w-100 text-dark fw-bold">Xem giỏ hàng</a>
    </div>
@endif

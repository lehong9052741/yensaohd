<div class="p-3 brand-light border-bottom"><strong>Giỏ hàng ({{ $cartCount }})</strong></div>
<div class="header-cart-content p-2">
    @forelse($cart as $id => $item)
        <div class="d-flex gap-2 align-items-center py-2 border-bottom position-relative cart-item">
            <!-- <img src="{{ asset('storage/' . ($item['image'] ?? '')) }}" alt="" class="rounded header-cart-item-img"> -->
            <div class="flex-grow-1">
                <div class="fw-medium">{{ $item['name'] }}</div>
                <div class="small text-muted">{{ number_format($item['price'],0,',','.') }}₫ × {{ $item['quantity'] }}</div>
            </div>
            <form action="/cart/remove/{{ $id }}" method="POST" class="d-inline remove-cart-item-form">
                @csrf
                <button type="submit" class="btn btn-sm btn-link text-danger p-0 remove-cart-btn">
                    <i class="bi bi-trash" style="font-size: 1.2rem;"></i>
                </button>
            </form>
        </div>
    @empty
        <div class="text-center py-4 text-muted">
            <span class="material-icons-outlined text-white header-cart-empty-icon">shopping_cart</span>
            <p class="mt-2 mb-0">Giỏ hàng trống</p>
        </div>
    @endforelse
</div>
@if($cartCount > 0)
    <div class="p-3 brand-light border-top">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <strong>Tổng:</strong>
            <strong>{{ number_format($total,0,',','.') }}₫</strong>
        </div>
        <a href="/cart" class="btn btn-warning w-100 text-dark fw-bold">Xem giỏ hàng</a>
    </div>
@endif

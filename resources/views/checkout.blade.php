@extends('layouts.master')

@section('content')
<div class="container my-5">
    <div class="checkout-wrapper">
        <!-- Coupon Section -->
        <div class="coupon-section">
            <p class="mb-0">
                <i class="bi bi-tag-fill"></i> 
                Bạn có mã ưu đãi? 
                <a href="#" data-bs-toggle="collapse" data-bs-target="#couponCollapse">Ấn vào đây để nhập mã</a>
            </p>
            <div class="collapse mt-3" id="couponCollapse">
                <div class="coupon-input-group">
                    <input type="text" class="form-control" placeholder="Mã ưu đãi">
                    <button class="btn btn-apply">Áp dụng</button>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Payment Information -->
            <div class="col-lg-7">
                <div class="checkout-section">
                    <h3 class="section-heading">THÔNG TIN THANH TOÁN</h3>
                    
                    <form action="/checkout" method="POST" id="checkoutForm">
                        @csrf
                        
                        <!-- Full Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <!-- Phone and Email -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Địa chỉ email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>

                        <!-- City and District -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                <select class="form-select" id="city" name="city" required>
                                    <option value="">Chọn tỉnh/thành phố</option>
                                    <option value="Hồ Chí Minh">Hồ Chí Minh</option>
                                    <option value="Hà Nội">Hà Nội</option>
                                    <option value="Đà Nẵng">Đà Nẵng</option>
                                    <option value="Cần Thơ">Cần Thơ</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="district" class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                <select class="form-select" id="district" name="district" required>
                                    <option value="">Chọn quận huyện</option>
                                </select>
                            </div>
                        </div>

                        <!-- Ward and Address -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ward" class="form-label">Xã/Phường <span class="text-danger">*</span></label>
                                <select class="form-select" id="ward" name="ward" required>
                                    <option value="">Chọn xã/phường</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="Ví dụ: Số 20, ngõ 90" required>
                            </div>
                        </div>

                        <!-- Shipping to Different Address -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="differentAddress">
                                <label class="form-check-label" for="differentAddress">
                                    Giao hàng đến một địa chỉ khác?
                                </label>
                            </div>
                        </div>

                        <!-- Order Notes -->
                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú đơn hàng (tùy chọn)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Ghi chú về đơn hàng, ví dụ: thời gian hay chỉ dẫn địa điểm giao hàng chi tiết hơn."></textarea>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Order Summary -->
            <div class="col-lg-5">
                <div class="order-summary">
                    <h3 class="summary-heading">ĐƠN HÀNG CỦA BẠN</h3>
                    
                    <div class="summary-table">
                        <div class="summary-header">
                            <span>SẢN PHẨM</span>
                            <span>TẠM TÍNH</span>
                        </div>

                        @foreach($cart as $item)
                        <div class="summary-item">
                            <div class="item-name">
                                {{ $item['name'] }} <span class="item-qty">× {{ $item['quantity'] }}</span>
                            </div>
                            <div class="item-price">{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}₫</div>
                        </div>
                        @endforeach

                        <div class="summary-row">
                            <span class="summary-label">Tạm tính</span>
                            <span class="summary-value">{{ number_format($total, 0, ',', '.') }}₫</span>
                        </div>

                        <div class="summary-row">
                            <span class="summary-label">Vận chuyển</span>
                            <span class="summary-value shipping-free">Giao hàng miễn phí</span>
                        </div>

                        <div class="summary-total">
                            <span class="total-label">Tổng</span>
                            <span class="total-value">{{ number_format($total, 0, ',', '.') }}₫</span>
                        </div>
                    </div>

                    <div class="payment-method">
                        <h4 class="payment-heading">Thanh toán khi nhận hàng</h4>
                        <p class="payment-description">
                            Mức hàng an toàn với hình thức <strong>"Thanh toán khi nhận hàng – COD"</strong> 
                            tại Vui. Dù bạn ở bất cứ đâu cũng hoàn toàn yên tâm vì khi nhận hàng 
                            có thể kiểm tra hàng hóa rồi mới thanh toán tiền.
                        </p>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="agreeTerms" required>
                            <label class="form-check-label" for="agreeTerms">
                                Tôi đã đọc và đồng ý với <a href="#">điều khoản và điều kiện</a> của website <span class="text-danger">*</span>
                            </label>
                        </div>

                        <button type="submit" form="checkoutForm" class="btn-place-order">
                            ĐẶT HÀNG
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple district/ward data (you can expand this)
    const locationData = {
        'Hồ Chí Minh': {
            'Quận 1': ['Phường Bến Nghé', 'Phường Bến Thành', 'Phường Cầu Kho'],
            'Quận 3': ['Phường 1', 'Phường 2', 'Phường 3'],
            'Quận 10': ['Phường 1', 'Phường 2', 'Phường 3']
        },
        'Hà Nội': {
            'Quận Ba Đình': ['Phường Cống Vị', 'Phường Điện Biên', 'Phường Đội Cấn'],
            'Quận Hoàn Kiếm': ['Phường Hàng Bạc', 'Phường Hàng Bài', 'Phường Hàng Trống']
        }
    };

    const citySelect = document.getElementById('city');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');

    citySelect.addEventListener('change', function() {
        const city = this.value;
        districtSelect.innerHTML = '<option value="">Chọn quận huyện</option>';
        wardSelect.innerHTML = '<option value="">Chọn xã/phường</option>';

        if (city && locationData[city]) {
            Object.keys(locationData[city]).forEach(district => {
                const option = document.createElement('option');
                option.value = district;
                option.textContent = district;
                districtSelect.appendChild(option);
            });
        }
    });

    districtSelect.addEventListener('change', function() {
        const city = citySelect.value;
        const district = this.value;
        wardSelect.innerHTML = '<option value="">Chọn xã/phường</option>';

        if (city && district && locationData[city][district]) {
            locationData[city][district].forEach(ward => {
                const option = document.createElement('option');
                option.value = ward;
                option.textContent = ward;
                wardSelect.appendChild(option);
            });
        }
    });
</script>
@endsection

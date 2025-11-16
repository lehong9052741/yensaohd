@extends('layouts.master')

@section('content')
<div class="container my-5">
    <!-- Progress Breadcrumb -->
    <div class="checkout-progress">
        <ul class="progress-steps">
            <div class="progress-line" style="width: 50%;\"></div>
            
            <li class="progress-step completed">
                <div class="progress-step-circle">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div class="progress-step-label">Giỏ hàng</div>
            </li>
            
            <li class="progress-step active">
                <div class="progress-step-circle">
                    <i class="bi bi-credit-card"></i>
                </div>
                <div class="progress-step-label">Thanh toán</div>
            </li>
            
            <li class="progress-step">
                <div class="progress-step-circle">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="progress-step-label">Hoàn thành</div>
            </li>
        </ul>
    </div>
    
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
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone and Email -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Địa chỉ email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- City and District -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                <select class="form-select @error('city') is-invalid @enderror" id="city" name="city" required>
                                    <option value="">Chọn tỉnh/thành phố</option>
                                    <option value="Hồ Chí Minh" {{ old('city') == 'Hồ Chí Minh' ? 'selected' : '' }}>Hồ Chí Minh</option>
                                    <option value="Hà Nội" {{ old('city') == 'Hà Nội' ? 'selected' : '' }}>Hà Nội</option>
                                    <option value="Đà Nẵng" {{ old('city') == 'Đà Nẵng' ? 'selected' : '' }}>Đà Nẵng</option>
                                    <option value="Cần Thơ" {{ old('city') == 'Cần Thơ' ? 'selected' : '' }}>Cần Thơ</option>
                                </select>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="district" class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                <select class="form-select @error('district') is-invalid @enderror" id="district" name="district" required>
                                    <option value="">Chọn quận huyện</option>
                                </select>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Ward and Address -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ward" class="form-label">Xã/Phường <span class="text-danger">*</span></label>
                                <select class="form-select @error('ward') is-invalid @enderror" id="ward" name="ward" required>
                                    <option value="">Chọn xã/phường</option>
                                </select>
                                @error('ward')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" placeholder="Ví dụ: Số 20, ngõ 90" required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="4" placeholder="Ghi chú về đơn hàng, ví dụ: thời gian hay chỉ dẫn địa điểm giao hàng chi tiết hơn.">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                        <h4 class="payment-heading">PHƯƠNG THỨC THANH TOÁN</h4>
                        
                        <!-- COD Payment -->
                        <div class="payment-option">
                            <div class="form-check">
                                <input class="form-check-input @error('payment_method') is-invalid @enderror" type="radio" name="payment_method" id="paymentCOD" value="cod" {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }} form="checkoutForm">
                                <label class="form-check-label payment-label" for="paymentCOD">
                                    <i class="bi bi-cash-coin text-success me-2"></i>
                                    <strong>Thanh toán khi nhận hàng (COD)</strong>
                                </label>
                            </div>
                            <div class="payment-description {{ old('payment_method', 'cod') == 'cod' ? '' : 'd-none' }}" id="codDescription">
                                <p class="mb-0">
                                    Thanh toán bằng tiền mặt khi nhận hàng. Bạn có thể kiểm tra hàng trước khi thanh toán.
                                </p>
                            </div>
                        </div>

                        <!-- Online Payment -->
                        <div class="payment-option">
                            <div class="form-check">
                                <input class="form-check-input @error('payment_method') is-invalid @enderror" type="radio" name="payment_method" id="paymentOnline" value="online" {{ old('payment_method') == 'online' ? 'checked' : '' }} form="checkoutForm">
                                <label class="form-check-label payment-label" for="paymentOnline">
                                    <i class="bi bi-credit-card text-primary me-2"></i>
                                    <strong>Thanh toán trực tuyến</strong>
                                </label>
                            </div>
                            <div class="payment-description {{ old('payment_method') == 'online' ? '' : 'd-none' }}" id="onlineDescription">
                                <p class="mb-3">Chọn phương thức thanh toán:</p>
                                
                                <!-- Bank Transfer -->
                                <div class="form-check mb-2">
                                    <input class="form-check-input @error('online_method') is-invalid @enderror" type="radio" name="online_method" id="bankTransfer" value="bank" {{ old('online_method') == 'bank' ? 'checked' : '' }} form="checkoutForm">
                                    <label class="form-check-label" for="bankTransfer">
                                        <i class="bi bi-bank text-info me-2"></i>
                                        Chuyển khoản ngân hàng
                                    </label>
                                </div>

                                <!-- VNPay -->
                                <div class="form-check mb-2">
                                    <input class="form-check-input @error('online_method') is-invalid @enderror" type="radio" name="online_method" id="vnpayPayment" value="vnpay" {{ old('online_method') == 'vnpay' ? 'checked' : '' }} form="checkoutForm">
                                    <label class="form-check-label" for="vnpayPayment">
                                        <img src="https://vnpay.vn/s1/statics.vnpay.vn/2023/9/06ncktiwd6dc1694418196384.png" alt="VNPay" style="height: 20px;" class="me-2">
                                        Ví VNPay
                                    </label>
                                </div>
                                @error('online_method')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            @error('payment_method')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-3 mt-4">
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

    // Restore old values on page load
    const oldCity = "{{ old('city') }}";
    const oldDistrict = "{{ old('district') }}";
    const oldWard = "{{ old('ward') }}";

    // Load districts if city is selected
    if (oldCity && locationData[oldCity]) {
        Object.keys(locationData[oldCity]).forEach(district => {
            const option = document.createElement('option');
            option.value = district;
            option.textContent = district;
            option.selected = district === oldDistrict;
            districtSelect.appendChild(option);
        });
    }

    // Load wards if district is selected
    if (oldCity && oldDistrict && locationData[oldCity][oldDistrict]) {
        locationData[oldCity][oldDistrict].forEach(ward => {
            const option = document.createElement('option');
            option.value = ward;
            option.textContent = ward;
            option.selected = ward === oldWard;
            wardSelect.appendChild(option);
        });
    }

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

    // Payment method handling
    const paymentCOD = document.getElementById('paymentCOD');
    const paymentOnline = document.getElementById('paymentOnline');
    const codDescription = document.getElementById('codDescription');
    const onlineDescription = document.getElementById('onlineDescription');

    paymentCOD.addEventListener('change', function() {
        if (this.checked) {
            codDescription.classList.remove('d-none');
            onlineDescription.classList.add('d-none');
        }
    });

    paymentOnline.addEventListener('change', function() {
        if (this.checked) {
            codDescription.classList.add('d-none');
            onlineDescription.classList.remove('d-none');
        }
    });

</script>
@endsection

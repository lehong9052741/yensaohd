# Hướng dẫn tích hợp VNPay

## 1. Cấu hình VNPay Sandbox

Trong file `.env`, cập nhật thông tin VNPay:

```env
# VNPay Configuration
VNPAY_TMN_CODE=YOUR_TMN_CODE
VNPAY_HASH_SECRET=YOUR_HASH_SECRET

# Admin email for notifications
ADMIN_EMAIL=admin@yensaohd.vn
```

### Lấy thông tin VNPay Sandbox:
1. Truy cập: https://sandbox.vnpayment.vn/
2. Đăng ký tài khoản demo
3. Lấy **TMN Code** và **Hash Secret** từ dashboard
4. Cập nhật vào file `.env`

## 2. Cấu hình Email

Để gửi email thông báo cho admin khi có đơn hàng thanh toán thành công:

### Sử dụng Gmail:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yensaohd.vn"
MAIL_FROM_NAME="Yến Sào Hoàng Đăng"

ADMIN_EMAIL=admin@yensaohd.vn
```

### Sử dụng Mailtrap (Testing):
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
```

## 3. Quy trình thanh toán

### COD (Thanh toán khi nhận hàng):
1. Khách chọn phương thức COD
2. Đặt hàng → Chuyển đến trang xác nhận
3. Trạng thái thanh toán: `pending`

### Chuyển khoản ngân hàng:
1. Khách chọn Chuyển khoản
2. Đặt hàng → Hiển thị QR code VietQR
3. Khách chuyển khoản theo thông tin
4. Trạng thái thanh toán: `awaiting_payment`
5. Admin xác nhận thủ công khi nhận được tiền

### VNPay (Tự động):
1. Khách chọn VNPay
2. Redirect đến cổng thanh toán VNPay
3. Khách thanh toán qua VNPay
4. VNPay callback về `/vnpay/return`
5. Hệ thống tự động:
   - Cập nhật trạng thái: `paid`
   - Lưu `transaction_id`
   - Lưu thời gian `paid_at`
   - Gửi email cho admin
6. Redirect về trang xác nhận đơn hàng

## 4. Test VNPay Sandbox

Thông tin test card VNPay Sandbox:

**Ngân hàng:** NCB (Ngân hàng Quốc Dân)
- Số thẻ: `9704198526191432198`
- Tên chủ thẻ: `NGUYEN VAN A`
- Ngày phát hành: `07/15`
- Mật khẩu OTP: `123456`

## 5. Database Schema

Bảng `orders` đã được thêm các cột:
- `transaction_id`: Mã giao dịch từ VNPay
- `paid_at`: Thời gian thanh toán thành công
- `payment_method`: cod, bank, vnpay
- `payment_status`: pending, awaiting_payment, paid, refunded

## 6. Email Template

Email gửi cho admin khi có đơn hàng mới thanh toán:
- File: `resources/views/emails/order-paid.blade.php`
- Nội dung: Thông tin đơn hàng, khách hàng, sản phẩm, tổng tiền
- Link: Xem chi tiết đơn hàng trong admin panel

## 7. API Endpoints

- `POST /checkout`: Tạo đơn hàng
- `GET /vnpay/return`: VNPay callback (IPN)
- `GET /order-confirmation`: Trang xác nhận đơn hàng

## 8. Lưu ý

1. **Production**: Đổi VNPay URL từ sandbox sang production
2. **Security**: Không commit file `.env` lên git
3. **Email**: Kiểm tra spam folder nếu không nhận được email
4. **Transaction ID**: Lưu để đối soát với VNPay

## 9. Troubleshooting

### Không nhận được email:
```bash
php artisan queue:work  # Nếu dùng queue
tail -f storage/logs/laravel.log  # Check logs
```

### VNPay callback lỗi:
- Check logs: `storage/logs/laravel.log`
- Verify `VNPAY_HASH_SECRET` đúng
- Kiểm tra URL return có đúng không

### Test local với VNPay:
- Sử dụng ngrok để expose local: `ngrok http 80`
- Cập nhật return URL trong VNPay dashboard

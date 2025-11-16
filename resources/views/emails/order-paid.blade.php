<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #28a745;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            background: #f9f9f9;
            padding: 20px;
            margin-top: 20px;
        }
        .order-info {
            background: white;
            padding: 15px;
            margin: 10px 0;
            border-left: 4px solid #28a745;
        }
        .order-items {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f4f4f4;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Đơn Hàng Mới Đã Thanh Toán</h1>
        </div>
        
        <div class="content">
            <p>Xin chào Admin,</p>
            <p>Có một đơn hàng mới đã được thanh toán thành công:</p>
            
            <div class="order-info">
                <h3>Thông tin đơn hàng</h3>
                <p><strong>Mã đơn hàng:</strong> {{ $order->order_number }}</p>
                <p><strong>Thời gian:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method }}</p>
                <p><strong>Trạng thái:</strong> <span style="color: #28a745;">Đã thanh toán</span></p>
                @if($order->transaction_id)
                <p><strong>Mã giao dịch:</strong> {{ $order->transaction_id }}</p>
                @endif
            </div>
            
            <div class="order-info">
                <h3>Thông tin khách hàng</h3>
                <p><strong>Họ tên:</strong> {{ $order->customer_name }}</p>
                <p><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
                <p><strong>Email:</strong> {{ $order->customer_email ?? 'N/A' }}</p>
                <p><strong>Địa chỉ:</strong> {{ $order->address }}, {{ $order->ward }}, {{ $order->district }}, {{ $order->city }}</p>
                @if($order->notes)
                <p><strong>Ghi chú:</strong> {{ $order->notes }}</p>
                @endif
            </div>
            
            <div class="order-items">
                <h3>Chi tiết sản phẩm</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->product_price, 0, ',', '.') }}₫</td>
                            <td>{{ number_format($item->subtotal, 0, ',', '.') }}₫</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right;"><strong>Tạm tính:</strong></td>
                            <td>{{ number_format($order->subtotal, 0, ',', '.') }}₫</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: right;"><strong>Phí vận chuyển:</strong></td>
                            <td>{{ number_format($order->shipping_fee, 0, ',', '.') }}₫</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: right;"><strong>Tổng cộng:</strong></td>
                            <td class="total">{{ number_format($order->total, 0, ',', '.') }}₫</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <p style="margin-top: 30px;">
                <a href="{{ url('/admin/orders/' . $order->id) }}" style="background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
                    Xem chi tiết đơn hàng
                </a>
            </p>
        </div>
        
        <div style="text-align: center; margin-top: 20px; color: #666; font-size: 12px;">
            <p>Email tự động từ hệ thống Yến Sào Hoàng Đăng</p>
        </div>
    </div>
</body>
</html>

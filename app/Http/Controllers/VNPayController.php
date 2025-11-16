<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class VNPayController extends Controller
{
    public function createPayment(Order $order)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.return');
        $vnp_TmnCode = env('VNPAY_TMN_CODE', 'DEMO'); // Mã website tại VNPAY 
        $vnp_HashSecret = env('VNPAY_HASH_SECRET', 'DEMO'); // Chuỗi bí mật

        $vnp_TxnRef = $order->order_number;
        $vnp_OrderInfo = 'Thanh toán đơn hàng #' . $order->order_number;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $order->total * 100; // VNPay yêu cầu số tiền nhân 100
        $vnp_Locale = 'vn';
        $vnp_BankCode = '';
        $vnp_IpAddr = request()->ip();

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return redirect($vnp_Url);
    }

    public function return(Request $request)
    {
        $vnp_HashSecret = env('VNPAY_HASH_SECRET', 'DEMO');
        $inputData = array();
        foreach ($request->all() as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
        
        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $hashData = "";
        $i = 0;
        
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        
        $vnpTranId = $request->vnp_TransactionNo;
        $vnpOrderInfo = $request->vnp_OrderInfo;
        $vnpResponseCode = $request->vnp_ResponseCode;
        $vnpAmount = $request->vnp_Amount / 100;
        $vnpTxnRef = $request->vnp_TxnRef; // Order number

        // Find order
        $order = Order::where('order_number', $vnpTxnRef)->first();

        if (!$order) {
            return redirect('/')->with('error', 'Không tìm thấy đơn hàng');
        }

        if ($secureHash == $vnp_SecureHash) {
            if ($vnpResponseCode == '00') {
                // Payment success
                $order->update([
                    'payment_status' => 'paid',
                    'transaction_id' => $vnpTranId,
                    'paid_at' => now(),
                ]);

                // Send email to admin
                $this->sendAdminNotification($order);

                // Store order info in session for confirmation page
                session()->put('order_confirmation', [
                    'order' => $order,
                    'payment_method_name' => 'Ví VNPay',
                    'payment_method' => 'vnpay',
                    'online_method' => 'vnpay',
                    'transaction_id' => $vnpTranId,
                ]);

                return redirect('/order-confirmation')->with('success', 'Thanh toán thành công!');
            } else {
                // Payment failed
                $order->update([
                    'payment_status' => 'pending',
                    'notes' => ($order->notes ? $order->notes . "\n" : '') . 'VNPay payment failed. Code: ' . $vnpResponseCode,
                ]);

                return redirect('/checkout')->with('error', 'Thanh toán thất bại. Vui lòng thử lại.');
            }
        } else {
            Log::error('VNPay invalid signature', [
                'order_number' => $vnpTxnRef,
                'response' => $request->all()
            ]);
            return redirect('/')->with('error', 'Chữ ký không hợp lệ');
        }
    }

    private function sendAdminNotification(Order $order)
    {
        try {
            $adminEmail = env('ADMIN_EMAIL', 'admin@yensaohd.vn');
            
            Mail::send('emails.order-paid', ['order' => $order], function ($message) use ($adminEmail, $order) {
                $message->to($adminEmail)
                        ->subject('Đơn hàng mới đã thanh toán #' . $order->order_number);
            });
        } catch (\Exception $e) {
            Log::error('Failed to send admin notification: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        return view('cart');
    }

    public function add(Request $request, Product $product)
    {
        $qty = max(1, (int) $request->input('quantity', 1));

        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $qty;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->display_price,
                'quantity' => $qty,
                'image' => $product->image,
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            $total = array_reduce($cart, function($s, $i) { return $s + ($i['price'] * $i['quantity']); }, 0);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm "' . $product->name . '" vào giỏ hàng',
                'cart_count' => count($cart),
                'cart_html' => view('partials.cart-dropdown', ['cart' => $cart, 'cartCount' => count($cart), 'total' => $total])->render()
            ]);
        }

        return redirect()->back()->with('cart_success', 'Đã thêm "' . $product->name . '" vào giỏ hàng');
    }

    public function remove(Request $request, $id)
    {
        $cart = session()->get('cart', []);
        $productName = '';

        if (isset($cart[$id])) {
            $productName = $cart[$id]['name'];
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('cart_removed', 'Đã xóa "' . $productName . '" khỏi giỏ hàng');
    }

    public function update(Request $request, $id)
    {
        $qty = max(1, (int) $request->input('quantity', 1));

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $qty;
            session()->put('cart', $cart);
            
            // Support AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật giỏ hàng thành công',
                    'cart_count' => count($cart)
                ]);
            }
            
            return redirect('/cart')->with('success', 'Cập nhật giỏ hàng thành công');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại trong giỏ hàng'
            ], 404);
        }
        
        return redirect('/cart')->with('error', 'Sản phẩm không tồn tại trong giỏ hàng');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect('/cart');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect('/cart')->with('error', 'Giỏ hàng của bạn đang trống');
        }

        // Clear previous order confirmation when starting new checkout
        session()->forget('order_confirmation');

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout', compact('cart', 'total'));
    }

    public function processCheckout(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'city' => 'required|string',
            'district' => 'required|string',
            'ward' => 'required|string',
            'address' => 'required|string',
            'notes' => 'nullable|string',
            'payment_method' => 'required|in:cod,online',
            'online_method' => 'required_if:payment_method,online|in:bank,vnpay',
        ]);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect('/cart')->with('error', 'Giỏ hàng của bạn đang trống');
        }

        DB::beginTransaction();
        
        try {
            // Calculate totals
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $shipping_fee = $subtotal >= 1000000 ? 0 : 30000; // Free shipping over 1M
            $total = $subtotal + $shipping_fee;

            // Create order
            $paymentMethod = $validated['payment_method'];
            $onlineMethod = $validated['online_method'] ?? null;
            
            // Determine payment method display name
            $paymentMethodName = 'Thanh toán khi nhận hàng (COD)';
            if ($paymentMethod === 'online') {
                if ($onlineMethod === 'bank') {
                    $paymentMethodName = 'Chuyển khoản ngân hàng';
                } elseif ($onlineMethod === 'vnpay') {
                    $paymentMethodName = 'Ví VNPay';
                }
            }
            
            $order = Order::create([
                'user_id' => auth()->id(),
                'customer_name' => $validated['name'],
                'customer_phone' => $validated['phone'],
                'customer_email' => $validated['email'],
                'city' => $validated['city'],
                'district' => $validated['district'],
                'ward' => $validated['ward'],
                'address' => $validated['address'],
                'notes' => $validated['notes'],
                'subtotal' => $subtotal,
                'shipping_fee' => $shipping_fee,
                'discount' => 0,
                'total' => $total,
                'status' => 'pending',
                'payment_method' => $paymentMethod === 'cod' ? 'cod' : $onlineMethod,
                'payment_status' => $paymentMethod === 'cod' ? 'pending' : 'awaiting_payment',
            ]);

            // Create order items and update product quantities
            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'product_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Update product quantity and sold count
                $product = Product::find($item['id']);
                if ($product) {
                    $product->decrement('quantity', $item['quantity']);
                    $product->increment('sold_count', $item['quantity']);
                }
            }

            DB::commit();

            // Clear cart
            session()->forget('cart');
            
            // If VNPay payment, redirect to VNPay
            if ($paymentMethod === 'online' && $onlineMethod === 'vnpay') {
                return app(\App\Http\Controllers\VNPayController::class)->createPayment($order);
            }
            
            // For COD and Bank transfer, go to confirmation page
            session()->put('order_confirmation', [
                'order' => $order,
                'payment_method_name' => $paymentMethodName,
                'payment_method' => $paymentMethod,
                'online_method' => $onlineMethod,
            ]);
            
            return redirect('/order-confirmation')->with('success', 'Đặt hàng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function orderConfirmation()
    {
        $orderData = session('order_confirmation');
        
        if (!$orderData) {
            return redirect('/')->with('error', 'Không tìm thấy thông tin đơn hàng');
        }
        
        return view('order-confirmation', $orderData);
    }
}

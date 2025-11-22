<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Get session identifier for guest users
     */
    private function getSessionId()
    {
        if (!session()->has('cart_session_id')) {
            session()->put('cart_session_id', uniqid('cart_', true));
        }
        return session('cart_session_id');
    }

    /**
     * Get cart items from database
     */
    private function getCartItems()
    {
        $query = CartItem::with('product');
        
        if (auth()->check()) {
            // For logged in users, get items by user_id OR session_id (for items added before login)
            $query->where(function($q) {
                $q->where('user_id', auth()->id())
                  ->orWhere(function($q2) {
                      $sessionId = session('cart_session_id');
                      if ($sessionId) {
                          $q2->where('session_id', $sessionId)
                             ->whereNull('user_id');
                      }
                  });
            });
        } else {
            $query->where('session_id', $this->getSessionId())
                  ->whereNull('user_id');
        }
        
        return $query->get();
    }

    /**
     * Format cart items for view compatibility
     */
    private function formatCartForView($cartItems)
    {
        $cart = [];
        foreach ($cartItems as $item) {
            if ($item->product) {
                $cart[$item->product_id] = [
                    'id' => $item->product_id,
                    'name' => $item->product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'image' => $item->product->image,
                    'weight' => $item->product->weight,
                ];
            }
        }
        return $cart;
    }

    public function index()
    {
        return view('cart');
    }

    public function add(Request $request, Product $product)
    {
        $qty = max(1, (int) $request->input('quantity', 1));

        // Check if item already exists in cart
        $cartItem = CartItem::where('product_id', $product->id)
            ->where(function($query) {
                if (auth()->check()) {
                    $query->where('user_id', auth()->id());
                } else {
                    $query->where('session_id', $this->getSessionId());
                }
            })
            ->first();

        if ($cartItem) {
            // Update quantity if already exists
            $cartItem->increment('quantity', $qty);
        } else {
            // Create new cart item
            CartItem::create([
                'session_id' => $this->getSessionId(),
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $qty,
                'price' => $product->display_price,
            ]);
        }

        // Get updated cart for response
        $cartItems = $this->getCartItems();
        $cart = $this->formatCartForView($cartItems);
        $total = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm "' . $product->name . '" vào giỏ hàng',
                'cart_count' => $cartItems->count(),
                'cart_html' => view('partials.cart-dropdown', [
                    'cart' => $cart,
                    'cartCount' => $cartItems->count(),
                    'total' => $total
                ])->render()
            ]);
        }

        return redirect()->back()->with('cart_success', 'Đã thêm "' . $product->name . '" vào giỏ hàng');
    }

    public function remove(Request $request, $id)
    {
        // Ensure session ID exists for guest users
        if (!auth()->check()) {
            $this->getSessionId();
        }
        
        // Log for debugging
        \Log::info('CartController::remove called', [
            'product_id' => $id,
            'auth_check' => auth()->check(),
            'user_id' => auth()->id(),
            'session_id' => $this->getSessionId(),
        ]);
        
        // Find cart item - prioritize user_id for logged in users
        if (auth()->check()) {
            $cartItem = CartItem::where('product_id', $id)
                ->where(function($query) {
                    $query->where('user_id', auth()->id())
                          ->orWhere(function($q2) {
                              $sessionId = session('cart_session_id');
                              if ($sessionId) {
                                  $q2->where('session_id', $sessionId)
                                     ->whereNull('user_id');
                              }
                          });
                })
                ->first();
        } else {
            $cartItem = CartItem::where('product_id', $id)
                ->where('session_id', $this->getSessionId())
                ->whereNull('user_id')
                ->first();
        }

        \Log::info('CartItem found', ['cart_item' => $cartItem ? $cartItem->toArray() : null]);

        $productName = '';
        if ($cartItem) {
            if ($cartItem->product) {
                $productName = $cartItem->product->name;
            }
            // Delete from database
            $deleted = $cartItem->delete();
            \Log::info('CartItem deleted', ['success' => $deleted]);
        } else {
            \Log::warning('CartItem not found for removal');
        }

        // Get updated cart after deletion
        $cartItems = $this->getCartItems();
        $cart = $this->formatCartForView($cartItems);
        $total = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

        // Support AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $productName ? 'Đã xóa "' . $productName . '" khỏi giỏ hàng' : 'Đã xóa sản phẩm khỏi giỏ hàng',
                'cart_count' => $cartItems->count(),
                'cart_html' => view('partials.cart-dropdown', [
                    'cart' => $cart,
                    'cartCount' => $cartItems->count(),
                    'total' => $total
                ])->render()
            ]);
        }

        return redirect()->back()->with('cart_removed', $productName ? 'Đã xóa "' . $productName . '" khỏi giỏ hàng' : 'Đã xóa sản phẩm khỏi giỏ hàng');
    }

    public function update(Request $request, $id)
    {
        // Ensure session ID exists for guest users
        if (!auth()->check()) {
            $this->getSessionId();
        }
        
        $qty = max(1, (int) $request->input('quantity', 1));

        // Find cart item - handle both logged in users and items from before login
        if (auth()->check()) {
            $cartItem = CartItem::where('product_id', $id)
                ->where(function($query) {
                    $query->where('user_id', auth()->id())
                          ->orWhere(function($q2) {
                              $sessionId = session('cart_session_id');
                              if ($sessionId) {
                                  $q2->where('session_id', $sessionId)
                                     ->whereNull('user_id');
                              }
                          });
                })
                ->first();
        } else {
            $cartItem = CartItem::where('product_id', $id)
                ->where('session_id', $this->getSessionId())
                ->whereNull('user_id')
                ->first();
        }

        if ($cartItem) {
            // Update quantity in database
            $cartItem->update(['quantity' => $qty]);
            
            // Get updated cart items
            $cartItems = $this->getCartItems();
            $cart = $this->formatCartForView($cartItems);
            $total = $cartItems->sum(function($item) {
                return $item->price * $item->quantity;
            });
            
            // Support AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Cập nhật giỏ hàng thành công',
                    'cart_count' => $cartItems->count(),
                    'cart_html' => view('partials.cart-dropdown', [
                        'cart' => $cart,
                        'cartCount' => $cartItems->count(),
                        'total' => $total
                    ])->render()
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
        CartItem::where(function($query) {
            if (auth()->check()) {
                $query->where('user_id', auth()->id());
            } else {
                $query->where('session_id', $this->getSessionId());
            }
        })->delete();
        
        return redirect('/cart');
    }

    public function checkout()
    {
        // Get cart items from database
        $cartItems = $this->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect('/cart')->with('error', 'Giỏ hàng của bạn đang trống');
        }

        // Clear previous order confirmation when starting new checkout
        session()->forget('order_confirmation');

        // Format cart for view
        $cart = $this->formatCartForView($cartItems);
        $total = $cartItems->sum(function($item) {
            return $item->price * $item->quantity;
        });

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

        // Get cart items from database
        $cartItems = $this->getCartItems();
        
        if ($cartItems->isEmpty()) {
            return redirect('/cart')->with('error', 'Giỏ hàng của bạn đang trống');
        }

        DB::beginTransaction();
        
        try {
            // Calculate totals
            $subtotal = $cartItems->sum(function($item) {
                return $item->price * $item->quantity;
            });

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
            foreach ($cartItems as $cartItem) {
                if ($cartItem->product) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'product_name' => $cartItem->product->name,
                        'product_price' => $cartItem->price,
                        'quantity' => $cartItem->quantity,
                        'subtotal' => $cartItem->price * $cartItem->quantity,
                    ]);

                    // Update product quantity and sold count
                    $cartItem->product->decrement('quantity', $cartItem->quantity);
                    $cartItem->product->increment('sold_count', $cartItem->quantity);
                }
            }

            DB::commit();

            // Clear cart from database
            CartItem::where(function($query) {
                if (auth()->check()) {
                    $query->where('user_id', auth()->id());
                } else {
                    $query->where('session_id', $this->getSessionId());
                }
            })->delete();
            
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

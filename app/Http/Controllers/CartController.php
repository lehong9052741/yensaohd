<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
                'price' => $product->price,
                'quantity' => $qty,
                'image' => $product->image,
            ];
        }

        session()->put('cart', $cart);

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
            return redirect('/cart')->with('success', 'Cập nhật giỏ hàng thành công');
        }

        return redirect('/cart')->with('error', 'Sản phẩm không tồn tại trong giỏ hàng');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect('/cart');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /** Display a listing of products. */
    public function index()
    {
        $products = Product::paginate(9);
        return view('products.index', compact('products'));
    }

    /** Display the specified product. */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /** Display home page with best sellers */
    public function home()
    {
        // Best sellers
        $bestSellers = Product::where('is_best_seller', true)
            ->orWhere('sold_count', '>', 0)
            ->orderBy('sold_count', 'desc')
            ->take(10)
            ->get();

        // Products by category
        $yenTho = Product::where('category', 'Yến Thô')->take(8)->get();
        $yenTinhChe = Product::where('category', 'Yến Tinh Chế')->take(8)->get();
        $yenChungSan = Product::where('category', 'Yến Chưng Sẵn')->take(8)->get();

        return view('home', compact('bestSellers', 'yenTho', 'yenTinhChe', 'yenChungSan'));
    }

    /** Display a listing of products. */
    public function index(Request $request)
    {
        $query = Product::query();
        
        // Filter by search keyword
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', '%' . $search . '%')
                  ->orWhere('description', 'ilike', '%' . $search . '%')
                  ->orWhere('category', 'ilike', '%' . $search . '%');
            });
        }
        
        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }
        
        $products = $query->paginate(12);
        $searchKeyword = $request->search ?? '';
        
        return view('products.index', compact('products', 'searchKeyword'));
    }

    /** Display products on promotion/sale */
    public function promotions(Request $request)
    {
        $query = Product::where(function($q) {
            $q->where('sale_price', '>', 0)
              ->orWhere('discount_percent', '>', 0);
        });
        
        // Apply sorting based on request
        $sort = $request->get('sort');
        
        if ($sort === 'discount') {
            // Sort by discount percentage (highest first)
            $query->orderBy('discount_percent', 'desc');
        } elseif ($sort === 'newest') {
            // Sort by newest (created_at)
            $query->orderBy('created_at', 'desc');
        } else {
            // Default: sort by discount percentage
            $query->orderBy('discount_percent', 'desc');
        }
        
        $products = $query->paginate(12);
        
        return view('promotions', compact('products'));
    }

    /** Display the specified product. */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
}

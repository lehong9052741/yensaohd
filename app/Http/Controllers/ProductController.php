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

        // Load customer reviews from JSON
        $reviewsPath = public_path('data/customer-reviews.json');
        $customerReviews = [];
        if (file_exists($reviewsPath)) {
            $reviewsData = json_decode(file_get_contents($reviewsPath), true);
            $customerReviews = $reviewsData['reviews'] ?? [];
        }

        return view('home', compact('bestSellers', 'yenTho', 'yenTinhChe', 'yenChungSan', 'customerReviews'));
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
        $currentCategory = null;
        if ($request->has('category') && $request->category) {
            $currentCategory = $request->category;
            $query->where('category', $currentCategory);
        }
        
        // Apply sorting
        $sort = $request->get('sort');
        switch ($sort) {
            case 'price_asc':
                $query->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'discount':
                $query->orderBy('discount_percent', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc');
                break;
        }
        
        $products = $query->paginate(12);
        $searchKeyword = $request->search ?? '';
        
        // Get related products from other categories
        $relatedProducts = collect();
        if ($currentCategory) {
            $relatedProducts = Product::where('category', '!=', $currentCategory)
                ->inRandomOrder()
                ->take(8)
                ->get();
        }
        
        // Load yến thô information from JSON
        $yenThoInfoPath = public_path('data/yen-tho-info.json');
        $yenThoInfo = [];
        if (file_exists($yenThoInfoPath)) {
            $yenThoInfo = json_decode(file_get_contents($yenThoInfoPath), true);
        }
        
        return view('products.index', compact('products', 'searchKeyword', 'relatedProducts', 'yenThoInfo'));
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

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Product;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share categories with all views
        View::composer('*', function ($view) {
            $categories = Product::select('category')
                ->distinct()
                ->whereNotNull('category')
                ->orderBy('category')
                ->pluck('category');
            
            $view->with('globalCategories', $categories);
        });
    }
}

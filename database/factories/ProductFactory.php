<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $categories = ['Yến Thô', 'Yến Tinh Chế', 'Yến Chưng Sẵn'];
        $name = $this->faker->sentence(3);
        $originalPrice = $this->faker->numberBetween(100000, 2000000);
        
        // 50% chance có sale
        $hasDiscount = $this->faker->boolean(50);
        $discountPercent = $hasDiscount ? $this->faker->randomElement([10, 15, 20, 25, 30, 35, 40]) : 0;
        $salePrice = $hasDiscount ? round($originalPrice * (100 - $discountPercent) / 100) : null;

        return [
            'name' => $name,
            'slug' => \Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1, 9999),
            'description' => $this->faker->paragraph(),
            'price' => $hasDiscount ? $salePrice : $originalPrice,
            'original_price' => $hasDiscount ? $originalPrice : null,
            'sale_price' => $salePrice,
            'discount_percent' => $discountPercent,
            'category' => $this->faker->randomElement($categories),
            'image' => null,
            'is_best_seller' => $this->faker->boolean(30), // 30% là best seller
            'sold_count' => $this->faker->numberBetween(0, 500),
            'quantity' => $this->faker->numberBetween(0, 100), // 0 = hết hàng
        ];
    }
}

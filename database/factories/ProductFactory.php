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

        return [
            'name' => $name,
            'slug' => \Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1, 9999),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(100000, 2000000),
            'category' => $this->faker->randomElement($categories),
            'image' => null,
        ];
    }
}

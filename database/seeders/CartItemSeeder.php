<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some products
        $products = Product::take(3)->get();
        
        if ($products->count() > 0) {
            // Add items to session cart for demonstration
            $sessionId = 'demo_session_' . time();
            
            foreach ($products as $index => $product) {
                DB::table('cart_items')->insert([
                    'session_id' => $sessionId,
                    'user_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $index + 1, // 1, 2, 3 items
                    'price' => $product->price,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            $this->command->info('Cart items seeded successfully with session: ' . $sessionId);
        } else {
            $this->command->warn('No products found. Please seed products first.');
        }
    }
}

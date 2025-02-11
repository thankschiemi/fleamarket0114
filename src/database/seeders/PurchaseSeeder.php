<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\User;

class PurchaseSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('email', 'default@example.com')->first();
        $product = Product::first();

        Purchase::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'payment_method' => 'credit_card',
            'status' => 'completed',
            'purchase_date' => now(),
        ]);
    }
}

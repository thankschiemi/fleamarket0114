<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\User;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $product = Product::first();
        Favorite::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }
}

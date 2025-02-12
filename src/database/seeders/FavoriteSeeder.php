<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\User;

class FavoriteSeeder extends Seeder
{
    public function run()
    {
        $product1 = Product::find(1);
        if ($product1 && User::find(1)) {
            Favorite::firstOrCreate(['user_id' => 1, 'product_id' => $product1->id]);
        }

        $product2 = Product::find(2);
        if ($product2 && User::find(2)) {
            Favorite::firstOrCreate(['user_id' => 2, 'product_id' => $product2->id]);
        }
    }
}

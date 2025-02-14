<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $product1 = Product::find(1);
        if ($product1) {
            $product1->categories()->attach([1, 5]);
        }

        $product2 = Product::find(2);
        if ($product2) {
            $product2->categories()->attach([2]);
        }

        $product3 = Product::find(3);
        if ($product3) {
            $product3->categories()->attach([10]);
        }

        $product4 = Product::find(4);
        if ($product4) {
            $product4->categories()->attach([1, 5]);
        }

        $product5 = Product::find(5);
        if ($product5) {
            $product5->categories()->attach([2]);
        }

        $product6 = Product::find(6);
        if ($product6) {
            $product6->categories()->attach([2]);
        }

        $product7 = Product::find(7);
        if ($product7) {
            $product7->categories()->attach([1, 4]);
        }

        $product8 = Product::find(8);
        if ($product8) {
            $product8->categories()->attach([10]);
        }

        $product9 = Product::find(9);
        if ($product9) {
            $product9->categories()->attach([10]);
        }

        $product10 = Product::find(10);
        if ($product10) {
            $product10->categories()->attach([6, 4]);
        }
    }
}

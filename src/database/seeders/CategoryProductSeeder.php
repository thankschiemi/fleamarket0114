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
        // 商品ID 1 (腕時計) → カテゴリ: ファッション, メンズ
        $product1 = Product::find(1);
        $product1->categories()->attach([1, 5]);

        // 商品ID 2 (HDD) → カテゴリ: 家電
        $product2 = Product::find(2);
        $product2->categories()->attach([2]);

        // 商品ID 3 (玉ねぎ3束) → カテゴリ: キッチン
        $product3 = Product::find(3);
        $product3->categories()->attach([10]);

        // 商品ID 4 (革靴) → カテゴリ: ファッション, メンズ
        $product4 = Product::find(4);
        $product4->categories()->attach([1, 5]);

        // 商品ID 5 (ノートPC) → カテゴリ: 家電
        $product5 = Product::find(5);
        $product5->categories()->attach([2]);

        // 商品ID 6 (マイク) → カテゴリ: 家電
        $product6 = Product::find(6);
        $product6->categories()->attach([2]);

        // 商品ID 7 (ショルダーバッグ) → カテゴリ: ファッション、レディ－ス
        $product7 = Product::find(7);
        $product7->categories()->attach([1, 4]);

        // 商品ID 8 (タンブラー) → カテゴリ: キッチン
        $product8 = Product::find(8);
        $product8->categories()->attach([10]);

        // 商品ID 9 (コーヒーミル) → カテゴリ: キッチン
        $product9 = Product::find(9);
        $product9->categories()->attach([10]);

        // 商品ID 10 (メイクセット) → カテゴリ: コスメ,レディース
        $product10 = Product::find(10);
        $product10->categories()->attach([6, 4]);
    }
}

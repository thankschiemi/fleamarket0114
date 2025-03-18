<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // ユーザー取得
        $userA = User::where('email', 'usera@example.com')->first();
        $userB = User::where('email', 'userb@example.com')->first();

        // ユーザーAの商品（C001〜C005）
        $productsA = [
            ['name' => '腕時計', 'brand' => 'kesio', 'price' => 15000, 'description' => 'メンズ腕時計', 'img_url' => 'Armani+Mens+Clock.jpg', 'condition' => '良好'],
            ['name' => 'HDD', 'brand' => 'パナソニッコ', 'price' => 5000, 'description' => '高速HDD', 'img_url' => 'HDD+Hard+Disk.jpg', 'condition' => '目立った傷なし'],
            ['name' => '玉ねぎ3束', 'brand' => '不明', 'price' => 300, 'description' => '新鮮な玉ねぎ', 'img_url' => 'iLoveIMG+d.jpg', 'condition' => 'やや傷あり'],
            ['name' => '革靴', 'brand' => 'グッチー', 'price' => 4000, 'description' => 'クラシックな革靴', 'img_url' => 'Leather+Shoes+Product+Photo.jpg', 'condition' => '状態が悪い'],
            ['name' => 'ノートPC', 'brand' => 'パナソニッコ', 'price' => 45000, 'description' => '高性能PC', 'img_url' => 'Living+Room+Laptop.jpg', 'condition' => '良好'],
        ];
        foreach ($productsA as $product) {
            Product::create(array_merge($product, ['user_id' => $userA->id, 'is_sold' => false]));
        }

        // ユーザーBの商品（C006〜C010）
        $productsB = [
            ['name' => 'マイク', 'brand' => 'パナソニッコ', 'price' => 8000, 'description' => '高音質マイク', 'img_url' => 'Music+Mic+4632231.jpg', 'condition' => '目立った傷なし'],
            ['name' => 'ショルダーバッグ', 'brand' => 'グッチー', 'price' => 3500, 'description' => 'おしゃれなバッグ', 'img_url' => 'Purse+fashion+pocket.jpg', 'condition' => 'やや傷あり'],
            ['name' => 'タンブラー', 'brand' => '不明', 'price' => 500, 'description' => '便利なタンブラー', 'img_url' => 'Tumbler+souvenir.jpg', 'condition' => '状態が悪い'],
            ['name' => 'コーヒーミル', 'brand' => 'スタパ', 'price' => 4000, 'description' => '手動コーヒーミル', 'img_url' => 'Waitress+with+Coffee+Grinder.jpg', 'condition' => '良好'],
            ['name' => 'メイクセット', 'brand' => 'ko-ko-', 'price' => 2500, 'description' => '便利なメイクセット', 'img_url' => 'Makeup+Set.jpg', 'condition' => '目立った傷なし'],
        ];
        foreach ($productsB as $product) {
            Product::create(array_merge($product, ['user_id' => $userB->id, 'is_sold' => false]));
        }
    }
}

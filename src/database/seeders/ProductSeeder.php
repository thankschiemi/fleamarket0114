<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'デフォルトユーザー',
            'email' => 'default@example.com',
            'password' => bcrypt('password'),
        ]);

        DB::table('products')->insert([
            [
                'user_id' => $user->id,
                'name' => '腕時計',
                'brand' => 'kesio',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'img_url' => 'Armani+Mens+Clock.jpg',
                'condition' => '良好',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'name' => 'HDD',
                'brand' => 'パナソニッコ',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'img_url' => 'HDD+Hard+Disk.jpg',
                'condition' => '目立った傷や汚れなし',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'name' => '玉ねぎ3束',
                'brand' => '不明',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束のセット',
                'img_url' => 'iLoveIMG+d.jpg',
                'condition' => 'やや傷や汚れあり',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'name' => '革靴',
                'brand' => 'グッチー',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'img_url' => 'Leather+Shoes+Product+Photo.jpg',
                'condition' => '状態が悪い',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'name' => 'ノートPC',
                'brand' => 'パナソニッコ',
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'img_url' => 'Living+Room+Laptop.jpg',
                'condition' => '良好',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'name' => 'マイク',
                'brand' => 'パナソニッコ',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'img_url' => 'Music+Mic+4632231.jpg',
                'condition' => '目立った傷や汚れなし',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'name' => 'ショルダーバッグ',
                'brand' => 'グッチー',
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'img_url' => 'Purse+fashion+pocket.jpg',
                'condition' => 'やや傷や汚れあり',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'name' => 'タンブラー',
                'brand' => '不明',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'img_url' => 'Tumbler+souvenir.jpg',
                'condition' => '状態が悪い',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'name' => 'コーヒーミル',
                'brand' => 'スタパ',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'img_url' => 'Waitress+with+Coffee+Grinder.jpg',
                'condition' => '良好',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'name' => 'メイクセット',
                'brand' => 'ko-ko-',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'img_url' => 'Makeup+Set.jpg',
                'condition' => '目立った傷や汚れなし',
                'is_sold' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}

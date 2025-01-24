<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
                'category' => 'ファッション',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'img_url' => 'Armani+Mens+Clock.jpg',
                'condition' => '良好',
                'is_sold' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'name' => 'HDD',
                'category' => '家電',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'img_url' => 'HDD+Hard+Disk.jpg',
                'condition' => '目立った傷や汚れなし',
                'is_sold' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user->id,
                'name' => '玉ねぎ3束',
                'category' => 'キッチン',
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
                'category' => 'メンズ',
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
                'category' => '家電',
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
                'category' => '家電',
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
                'category' => 'ファッション',
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
                'category' => 'キッチン',
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
                'category' => 'キッチン',
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
                'category' => 'コスメ',
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

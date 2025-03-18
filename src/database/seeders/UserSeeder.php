<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // 既存のデフォルトユーザーを追加
        if (!User::where('email', 'default@example.com')->exists()) {
            User::create([
                'name' => 'テストユーザー',
                'email' => 'default@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // ユーザーA（C001～C005の商品を出品）
        if (!User::where('email', 'usera@example.com')->exists()) {
            User::create([
                'name' => 'ユーザーA',
                'email' => 'usera@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // ユーザーB（C006～C010の商品を出品）
        if (!User::where('email', 'userb@example.com')->exists()) {
            User::create([
                'name' => 'ユーザーB',
                'email' => 'userb@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // ユーザーC（何も紐づいていないユーザー）
        if (!User::where('email', 'userc@example.com')->exists()) {
            User::create([
                'name' => 'ユーザーC',
                'email' => 'userc@example.com',
                'password' => bcrypt('password'),
            ]);
        }
    }
}

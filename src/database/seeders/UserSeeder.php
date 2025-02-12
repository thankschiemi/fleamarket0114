<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        if (!User::where('email', 'default@example.com')->exists()) {
            User::create([
                'name' => 'テストユーザー',
                'email' => 'default@example.com',
                'password' => bcrypt('password'),
            ]);
        }
    }
}

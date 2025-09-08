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

        if (!User::where('email', 'usera@example.com')->exists()) {
            User::create([
                'name' => 'ユーザーA',
                'email' => 'usera@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        if (!User::where('email', 'userb@example.com')->exists()) {
            User::create([
                'name' => 'ユーザーB',
                'email' => 'userb@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        if (!User::where('email', 'userc@example.com')->exists()) {
            User::create([
                'name' => 'ユーザーC',
                'email' => 'userc@example.com',
                'password' => bcrypt('password'),
            ]);
        }
    }
}

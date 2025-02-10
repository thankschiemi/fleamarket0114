<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 他のシーダーがあればここに追加
        $this->call([
            ProductSeeder::class,
            CategorySeeder::class,
            CategoryProductSeeder::class,
            UserSeeder::class,
            FavoriteSeeder::class,
        ]);
    }
}

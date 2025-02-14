<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\PurchaseSeeder;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            UserSeeder::class,
            ProductSeeder::class,
            PurchaseSeeder::class,
        ]);
    }

    /** @test */
    public function プロフィールページにユーザー情報が正しく表示される()
    {
        $user = User::where('email', 'default@example.com')->first();
        $this->actingAs($user);

        $response = $this->get('/mypage');
        $response->assertSee($user->name);
        $this->assertNotEmpty($user->products, '出品商品がありません');
        $this->assertNotEmpty($user->purchases, '購入商品がありません');
    }
}

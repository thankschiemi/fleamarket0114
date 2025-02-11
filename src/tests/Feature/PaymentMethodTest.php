<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Database\Seeders\UserSeeder;
use Database\Seeders\ProductSeeder;
use App\Models\User;
use App\Models\Product;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // 必要なデータをSeederで作成
        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);
    }

    /** @test */
    public function 支払い方法が正しく反映される()
    {
        $user = User::first();  // Seederで作成した最初のユーザーを取得
        $product = Product::first();  // Seederで作成した最初の商品を取得
        $this->actingAs($user);  // ログイン状態にする

        // 購入画面へアクセス
        $response = $this->get("/purchase/{$product->id}");
        $response->assertStatus(200);  // ステータス200を確認

        // 支払い方法が正しく表示されているか確認
        $response->assertSeeText('コンビニ払い');
        $response->assertSeeText('カード支払い');
    }
}

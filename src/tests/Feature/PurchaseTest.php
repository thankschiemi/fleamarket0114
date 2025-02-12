<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function ログインユーザーが購入できる()
    {
        $user = User::first();
        $product = Product::first();
        $this->actingAs($user);

        $response = $this->get("/purchase/{$product->id}/complete?session_id=test_session");

        $response->assertStatus(302);
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }
    /** @test */
    public function 購入した商品は商品一覧画面にて_sold_バッジが表示される()
    {
        $user = User::first();
        $product = Product::first();
        $this->actingAs($user);

        // 購入処理を実行
        $this->get("/purchase/{$product->id}/complete?session_id=test_session");

        // データベースを確認して is_sold が true になっているか確認
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'is_sold' => true,
        ]);
    }




    /** @test */
    public function 購入した商品がプロフィール購入一覧に追加されている()
    {
        $user = User::first();
        $product = Product::first();
        $this->actingAs($user);

        $response = $this->get("/purchase/{$product->id}/complete?session_id=test_session");

        $response = $this->get('/mypage?tab=buy');
        $response->assertSeeText($product->name);


        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }
}

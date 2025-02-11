<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Review;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function ログインユーザーはコメントを送信できる()
    {
        $user = User::first();
        $product = Product::first();

        $this->actingAs($user);

        $response = $this->post("/reviews/{$product->id}", [
            'comment' => '素晴らしい商品です！'
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('reviews', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'comment' => '素晴らしい商品です！',
        ]);
    }

    /** @test */
    public function 未ログインユーザーはコメントを送信できない()
    {
        $product = Product::first();

        $response = $this->post("/reviews/{$product->id}", [
            'comment' => 'ログインせずにコメントを試みます。'
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('reviews', [
            'comment' => 'ログインせずにコメントを試みます。',
        ]);
    }

    /** @test */
    public function コメントが空の場合はバリデーションエラーが発生する()
    {
        $user = User::first();
        $product = Product::first();

        $this->actingAs($user);

        $response = $this->post("/reviews/{$product->id}", [
            'comment' => ''
        ]);

        $response->assertSessionHasErrors('comment');
    }

    /** @test */
    public function コメントが256文字以上の場合はバリデーションエラーが発生する()
    {
        $user = User::first();
        $product = Product::first();

        $this->actingAs($user);

        $longComment = str_repeat('あ', 256);

        $response = $this->post("/reviews/{$product->id}", [
            'comment' => $longComment
        ]);

        $response->assertSessionHasErrors('comment');
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\User;
use App\Models\Favorite;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function test_いいねを押下すると_いいね数が増加する()
    {
        $user = User::first();
        $product = Product::first();
        $this->actingAs($user);

        Favorite::where('user_id', $user->id)->where('product_id', $product->id)->delete();

        $response = $this->post("/favorites/{$product->id}");
        $response->assertStatus(302);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }



    /** @test */
    public function いいね済みの商品は_アイコンが色変化する()
    {
        $user = User::first();
        $this->actingAs($user);

        $product = Product::first();
        Favorite::create(['user_id' => $user->id, 'product_id' => $product->id]);

        $response = $this->get("/item/{$product->id}");
        $response->assertSeeInOrder([
            'product-detail__icon-star',
            'product-detail__icon-star--active'
        ]);
    }

    /** @test */
    public function いいねを解除すると_いいね数が減少する()
    {
        $user = User::first();
        $this->actingAs($user);

        $product = Product::first();
        Favorite::create(['user_id' => $user->id, 'product_id' => $product->id]);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->post("/favorites/{$product->id}");
        $response->assertStatus(302);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }
}

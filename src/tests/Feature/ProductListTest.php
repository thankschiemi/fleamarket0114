<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\User;
use Tests\TestCase;
use Database\Seeders\ProductSeeder;

class ProductListTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ProductSeeder::class);
    }

    /** @test */
    public function 全商品が取得できる()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee(Product::all()->pluck('name')->toArray());
    }

    /** @test */
    public function 購入済み商品に_Sold_ラベルが表示される()
    {
        $product = Product::first();
        $product->is_sold = true;
        $product->save();

        $response = $this->get('/');
        $response->assertSee('Sold');
    }

    /** @test */
    public function 自分が出品した商品が表示されない()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get('/');

        $response->assertDontSee(Product::where('user_id', $user->id)->first()->name);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\User;
use App\Models\Favorite;
use Tests\TestCase;
use Database\Seeders\ProductSeeder;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ProductSeeder::class);
    }

    /** @test */
    public function いいねした商品だけが表示される()
    {
        $user = User::first();
        $this->actingAs($user);

        $favoriteProduct = Product::first();
        Favorite::create(['user_id' => $user->id, 'product_id' => $favoriteProduct->id]);

        $response = $this->get('/?tab=mylist');

        $response->assertSee($favoriteProduct->name);
    }


    /** @test */
    public function 購入済み商品に_Sold_ラベルが表示される()
    {
        $user = User::first();
        $this->actingAs($user);

        $product = Product::first();
        $product->is_sold = true;
        $product->save();

        Favorite::create(['user_id' => $user->id, 'product_id' => $product->id]);

        $response = $this->get('/?tab=mylist');

        $response->assertSee('Sold');
    }

    /** @test */
    public function 自分が出品した商品が表示されない()
    {
        $user = User::first();
        $this->actingAs($user);

        $response = $this->get('/?tab=mylist');

        $response->assertDontSee(Product::where('user_id', $user->id)->first()->name);
    }

    /** @test */
    public function 未認証の場合は何も表示されない()
    {
        $response = $this->get('/?tab=mylist');


        $response->assertRedirect('/login');
    }
}

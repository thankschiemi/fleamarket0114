<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\User;
use App\Models\Favorite;
use Tests\TestCase;
use Database\Seeders\ProductSeeder;
use Database\Seeders\FavoriteSeeder;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ProductSeeder::class);
        $this->seed(FavoriteSeeder::class);
    }

    /** @test */
    public function 商品名で部分一致検索ができる()
    {
        $response = $this->get('/?tab=recommend&query=腕');

        $response->assertStatus(200);
        $response->assertSee('腕時計');
    }

    /** @test */
    public function 検索状態がマイリストでも保持されている()
    {
        $user = User::first();
        $this->actingAs($user);

        // 腕時計がProductSeederで作成されている前提
        $product = Product::where('name', '腕時計')->first();

        // 腕時計をお気に入りに登録
        Favorite::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        $response = $this->get('/?tab=mylist&query=腕');
        $response->assertStatus(200);
        $response->assertSee('腕時計');
    }
}

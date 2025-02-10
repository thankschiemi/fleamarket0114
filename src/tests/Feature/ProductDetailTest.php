<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Tests\TestCase;
use Database\Seeders\ProductSeeder;

class ProductDetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ProductSeeder::class);
    }

    /** @test */
    public function 必要な情報がすべて商品詳細ページに表示される()
    {
        $product = Product::with('categories')->first();

        $response = $this->get('/item/' . $product->id);

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($product->brand);
        $response->assertSee(number_format($product->price));
        $response->assertSee($product->description);
        $response->assertSee($product->condition);

        foreach ($product->categories as $category) {
            $response->assertSee($category->name);
        }

        $response->assertSee("コメント (0)");
    }


    /** @test */
    public function 複数選択されたカテゴリが商品詳細ページに表示される()
    {
        $product = Product::with('categories')->first();

        $response = $this->get('/item/' . $product->id);
        $response->assertStatus(200);

        foreach ($product->categories as $category) {
            $response->assertSee($category->name);
        }
    }
}

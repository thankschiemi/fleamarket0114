<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use Database\Seeders\ProductSeeder;

class AddressChangeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ProductSeeder::class);
    }

    /** @test */
    public function 送付先住所が商品購入画面に正しく反映される()
    {
        /** @var \App\Models\User $user */

        $user = User::factory()->create(['postal_code' => '123-4567', 'address' => '東京都新宿区', 'building_name' => '新宿ビル']);
        $product = Product::first();
        $this->actingAs($user);

        $response = $this->get("/purchase/{$product->id}");

        $response->assertSee('123-4567');
        $response->assertSee('東京都新宿区');
        $response->assertSee('新宿ビル');
    }

    /** @test */
    public function 購入後に正しい送付先住所が紐づいている()
    {
        /** @var \App\Models\User $user */

        $user = User::factory()->create(['postal_code' => '987-6543', 'address' => '大阪市中央区', 'building_name' => '大阪タワー']);
        $product = Product::first();
        $this->actingAs($user);

        $this->post("/purchase/{$product->id}/checkout");

        $this->assertEquals('987-6543 大阪市中央区 大阪タワー', "{$user->postal_code} {$user->address} {$user->building_name}");
    }
}

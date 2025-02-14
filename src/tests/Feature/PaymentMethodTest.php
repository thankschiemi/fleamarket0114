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

        $this->seed(UserSeeder::class);
        $this->seed(ProductSeeder::class);
    }

    /** @test */
    public function 支払い方法が正しく反映される()
    {
        $user = User::first();
        $product = Product::first();
        $this->actingAs($user);

        $response = $this->get("/purchase/{$product->id}");
        $response->assertStatus(200);

        $response->assertSeeText('コンビニ払い');
        $response->assertSeeText('カード支払い');
    }
}

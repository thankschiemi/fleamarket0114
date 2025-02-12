<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductExhibitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'UserSeeder']); // シーダーを実行
    }


    /** @test */
    public function 商品を正しく出品できる()
    {
        $this->seed(\Database\Seeders\UserSeeder::class);
        $this->seed(\Database\Seeders\CategorySeeder::class);

        $user = User::where('email', 'default@example.com')->first();
        $this->actingAs($user);

        $category = Category::first();

        $formData = [
            'name' => 'テスト商品',
            'description' => 'これはテスト商品です。',
            'price' => 5000,
            'condition' => '新品',
            'category' => [$category->id],
            'image' => new UploadedFile(storage_path('app/public/images/default-product.jpeg'), 'default-product.jpeg', 'image/jpeg', null, true),
        ];

        $response = $this->post('/sell', $formData);

        $response->assertStatus(302);
        $response->assertRedirect('/mypage?tab=sell');

        // 商品がデータベースに存在するか確認
        $this->assertDatabaseHas('products', [
            'name' => 'テスト商品',
            'description' => 'これはテスト商品です。',
            'price' => 5000,
            'condition' => '新品',
        ]);
    }
}

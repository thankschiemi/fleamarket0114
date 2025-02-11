<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Database\Seeders\UserSeeder;

class ProductExhibitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class); // ユーザーをSeederで準備
    }

    /** @test */
    public function 商品を正しく出品できる()
    {
        $user = User::first(); // 最初のユーザーを取得
        $this->actingAs($user); // ログイン状態にする

        // 商品出品フォームのデータを作成
        $formData = [
            'name' => 'テスト商品',
            'description' => 'これはテスト商品です。',
            'price' => 5000,
            'condition' => '新品',
            'category' => [1, 2], // カテゴリーID（複数可）
            'image' => 'products/sample.jpg', // サンプル画像パス
        ];

        // 商品出品処理を実行
        $response = $this->post('/sell', $formData);

        // 商品が正しくデータベースに登録されているか確認
        $this->assertDatabaseHas('products', [
            'name' => 'テスト商品',
            'description' => 'これはテスト商品です。',
            'price' => 5000,
            'condition' => '新品',
            'user_id' => $user->id,
        ]);
    }
}

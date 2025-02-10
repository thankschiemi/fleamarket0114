<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Tests\TestCase;
use Database\Seeders\UserSeeder;


class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(UserSeeder::class); // ログインテスト用にユーザーを作成
    }

    /** @test */
    public function メールアドレスが未入力の場合_バリデーションメッセージが表示される()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    /** @test */
    public function パスワードが未入力の場合_バリデーションメッセージが表示される()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    /** @test */
    public function 入力情報が間違っている場合_エラーメッセージが表示される()
    {
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません。']);
    }

    /** @test */
    public function 正しい情報が入力された場合_ログイン処理が成功する()
    {
        $user = User::first();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password', // UserSeederで生成されたパスワード
        ]);

        $response->assertRedirect('/mypage/profile');
        $this->assertAuthenticatedAs($user);
    }
}

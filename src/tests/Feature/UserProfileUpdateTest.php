<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class UserProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function プロフィール編集画面に初期値が正しく表示される()
    {
        /** @var \App\Models\User $user */

        $user = User::factory()->create([
            'name' => '山田花子',
            'postal_code' => '100-0001',
            'address' => '東京都千代田区千代田1-1',
            'building_name' => 'アーバンシティ',
            'profile_image_path' => 'images/default-avatar.png',
        ]);


        $this->actingAs($user);
        $response = $this->get('/mypage/profile');
        $response->assertStatus(200);

        $response->assertSee('山田花子');
        $response->assertSee('100-0001');
        $response->assertSee('東京都千代田区千代田1-1');
        $response->assertSee('アーバンシティ');
        $response->assertSee('images/default-avatar.png');
    }
}

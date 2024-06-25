<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_ユーザー登録が成功()
    {
        // ストレージをモック
        Storage::fake('testing');

        // テストデータの準備
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'profile_image' => UploadedFile::fake()->image('profile.jpg')
        ];

        // POSTリクエストを送信
        $response = $this->post(route('register'), $data);

        // リダイレクトが正しいことを確認
        $response->assertRedirect(route('home'));

        // ユーザーがデータベースに存在することを確認
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com'
        ]);

        // ユーザーのプロフィール画像がストレージに存在することを確認
        $user = User::where('email', 'johndoe@example.com')->first();
        Storage::disk('public')->assertExists($user->profile_image);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_invalidなデータによるユーザー登録の失敗()
    {
        // 無効なデータの準備
        $data = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'different'
        ];

        // POSTリクエストを送信
        $response = $this->post(route('register'), $data);

        // バリデーションエラーが発生していることを確認
        $response->assertSessionHasErrors(['name', 'email', 'password']);
    }
}

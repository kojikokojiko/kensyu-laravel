<?php
namespace Tests\Feature;

    use App\Models\User;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Tests\TestCase;

class UserLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_correct_credentials()
    {
        // ユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt($password = 'password'),
        ]);

        // 正しい資格情報でログイン
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        // ログイン成功後、リダイレクトを確認
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_incorrect_credentials()
    {
        // ユーザーを作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // 誤った資格情報でログイン
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        // ログイン失敗後、エラーメッセージを確認
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_logout()
    {
        // ユーザーを作成してログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // ログアウトリクエストを送信
        $response = $this->post('/logout');

        // ログアウト成功後、リダイレクトを確認
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}

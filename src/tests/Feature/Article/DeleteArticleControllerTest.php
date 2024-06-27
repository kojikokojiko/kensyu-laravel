<?php

declare (strict_types=1);

namespace Feature\Article;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function login(User $user)
    {
        $this->actingAs($user);
    }

    public function test_記事が正常に削除される()
    {
        // テストデータの作成
        $user = User::factory()->create();
        $this->login($user);

        $article = Article::factory()->create(['user_id' => $user->id]);

        // リクエストの実行
        $response = $this->delete(route('articles.delete_article', $article));

        // アサーション
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success', 'Article deleted successfully.');

        // 記事がデータベースから削除されていることを確認
        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }

    public function test_未ログイン状態で記事を削除しようとするとリダイレクトされる()
    {
        // テストデータの作成
        $user = User::factory()->create();
        $article = Article::factory()->create(['user_id' => $user->id]);

        // リクエストの実行
        $response = $this->delete(route('articles.delete_article', $article));

        // アサーション
        $response->assertRedirect(route('login')); // ログインページにリダイレクトされることを確認
    }

    public function test_他のユーザーの記事を削除しようとするとエラーメッセージが表示される()
    {
        // テストデータの作成
        $user = User::factory()->create();
        $this->login($user);
        $otherUser = User::factory()->create();
        $article = Article::factory()->for($otherUser)->create();

        // 削除リクエストの実行
        $response = $this->delete(route('articles.delete_article', $article->id));

        // アサーション
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error', '他のユーザーの投稿は削除できません');

        // 削除されていないことを確認
        $this->assertDatabaseHas('articles', ['id' => $article->id]);
    }
}

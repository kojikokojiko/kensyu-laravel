<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleCreationTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        // データベースをリセット
        Artisan::call('migrate:fresh');
        // タグデータをシード
        $this->seed(\Database\Seeders\TagSeeder::class);
    }

    public function test_authenticated_user_can_create_article()
    {
        // ストレージファサードを使用してテスト中にファイルを保存
        Storage::fake('public');

        // 認証済みユーザーを作成してログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // タグデータを取得
        $tags = Tag::all();

        // サムネイルファイルを作成
        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');


        // 記事作成リクエストを送信
        $response = $this->post('/articles', [
            'title' => 'Test Article',
            'body' => 'This is the body of the test article.',
            'thumbnail' => $thumbnail,
            'tags' => $tags->pluck('id')->toArray(),
        ]);

        // 成功時のリダイレクトステータスコードを確認
        $response->assertStatus(302);

        // リダイレクトを確認
        $response->assertRedirect('/');

        // データベースに記事が作成されていることを確認
        $this->assertDatabaseHas('articles', [
            'title' => 'Test Article',
            'body' => 'This is the body of the test article.',
            'user_id' => $user->id,
        ]);

        // ストレージにサムネイルが保存されていることを確認
//        Storage::disk('public')->assertExists('thumbnails/' . $filename);
    }

    public function test_article_creation_requires_authentication()
    {
        // 認証されていない状態で記事作成リクエストを送信
        $response = $this->post('/articles', [
            'title' => 'Test Article',
            'body' => 'This is the body of the test article.',
        ]);

        // 認証を求めるリダイレクトを確認
        $response->assertRedirect('/login');
    }

    public function test_article_creation_requires_validation()
    {
        // 認証済みユーザーを作成してログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        // バリデーションエラーを引き起こすデータで記事作成リクエストを送信
        $response = $this->post('/articles', [
            'title' => '',
            'body' => '',
        ]);

        // セッションにエラーメッセージが含まれていることを確認
        $response->assertSessionHasErrors(['title', 'body']);
    }

}

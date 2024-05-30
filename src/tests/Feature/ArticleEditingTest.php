<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleEditingTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        // データベースをリセット
        Artisan::call('migrate:fresh');
        // タグデータをシード
        $this->seed(\Database\Seeders\TagSeeder::class);
    }
    public function test_authenticated_user_can_edit_own_article()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create(['user_id' => $user->id]);

        $tags = Tag::all();
        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');

        $response = $this->put("/articles/{$article->id}", [
            'title' => 'Updated Test Article',
            'body' => 'This is the updated body of the test article.',
            'thumbnail' => $thumbnail,
            'tags' => $tags->pluck('id')->toArray(),
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/');

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Updated Test Article',
            'body' => 'This is the updated body of the test article.',
        ]);

//        Storage::disk('public')->assertExists('thumbnails/' . $filename);
    }

    public function test_unauthenticated_user_cannot_edit_article()
    {
        $article = Article::factory()->create();

        $response = $this->put("/articles/{$article->id}", [
            'title' => 'Updated Test Article',
            'body' => 'This is the updated body of the test article.',
        ]);

        $response->assertRedirect('/login');
    }

    public function test_article_edit_requires_validation()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create(['user_id' => $user->id]);

        $response = $this->put("/articles/{$article->id}", [
            'title' => '',
            'body' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'body']);
    }

    public function test_user_cannot_edit_others_article()
    {
        // 記事を更新しようとするユーザー
        $user = User::factory()->create();
        $this->actingAs($user);

        // 別のユーザーが作成した記事
        $otherUser = User::factory()->create();
        $article = Article::factory()->create([
            'user_id' => $otherUser->id, // 記事の作成者を別のユーザーに設定
        ]);

        // 記事を更新しようとするリクエスト
        $response = $this->put("/articles/{$article->id}", [
            'title' => 'Updated Test Article',
            'body' => 'This is the updated body of the test article.',
        ]);

        // リダイレクトを確認
        $response->assertRedirect('/');
        // エラーメッセージを確認
        $response->assertSessionHasErrors('error');
    }

}

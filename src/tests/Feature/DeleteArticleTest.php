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

class DeleteArticleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // データベースをリセット
        Artisan::call('migrate:fresh');
        // タグデータをシード
        $this->seed(\Database\Seeders\TagSeeder::class);
    }
    public function test_user_can_delete_own_article()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->delete("/articles/{$article->id}");
        $response->assertRedirect('/');
        $response->assertSessionHas('success', 'Article deleted successfully.');
//        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }

    public function test_user_cannot_delete_others_article()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otherUser = User::factory()->create();
        $article = Article::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->delete("/articles/{$article->id}");
        // レスポンスを表示
//        dump($response);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors('errors');
        $this->assertDatabaseHas('articles', ['id' => $article->id]);
    }

    public function test_guest_cannot_delete_article()
    {
        $article = Article::factory()->create();

        $response = $this->delete("/articles/{$article->id}");

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('articles', ['id' => $article->id]);
    }

}

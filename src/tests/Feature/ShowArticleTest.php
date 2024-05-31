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

class ShowArticleTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        // データベースをリセット
        Artisan::call('migrate:fresh');
        // タグデータをシード
        $this->seed(\Database\Seeders\TagSeeder::class);
    }
    public function test_guest_can_view_article()
    {
        $user = User::factory()->create();

        $article = Article::factory()->create([
            'user_id' => $user->id,
            'title' => 'Sample Article',
            'body' => 'This is the body of the sample article.',
        ]);

        $response = $this->get("/articles/{$article->id}");

        $response->assertStatus(200);
        $response->assertSee('Sample Article');
        $response->assertSee('This is the body of the sample article.');
    }
    public function test_authenticated_user_can_view_article()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $article = Article::factory()->create([
            'user_id' => $user->id,
            'title' => 'Sample Article',
            'body' => 'This is the body of the sample article.',
        ]);

        $response = $this->get("/articles/{$article->id}");

        $response->assertStatus(200);
        $response->assertSee('Sample Article');
        $response->assertSee('This is the body of the sample article.');
    }

}

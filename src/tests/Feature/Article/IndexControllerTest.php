<?php

namespace Feature\Article;
use App\Models\Article;
use App\Models\Tag;
use App\Models\Thumbnail;
//use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

namespace Tests\Feature\Article;

use App\Models\Article;
use App\Models\User;
use App\Models\Thumbnail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_一覧画面表示()
    {
        // Arrange
        Storage::fake('testing');

        $user = User::factory()->create();
        $thumbnailPath = UploadedFile::fake()->image('thumbnail.jpg')->store('public/thumbnails');
        $profileImagePath = UploadedFile::fake()->image('profile.jpg')->store('public/profile_images');
        $user->profile_image =  str_replace('public/', '', $profileImagePath);;;;
        $user->save();

        $article = Article::factory()->create(['user_id' => $user->id]);
        Thumbnail::factory()->create(['article_id' => $article->id, 'path' => str_replace('public/', '', $thumbnailPath)]);

        // Act
        $response = $this->get(route('home'));

        // Assert
        $response->assertStatus(200);
        $response->assertSee($article->title);
        $response->assertSee($article->body);
        $response->assertSee($user->name);
        $response->assertSee(asset('storage/' . str_replace('public/', '', $thumbnailPath)));
        $response->assertSee(asset('storage/' . str_replace('public/', '', $profileImagePath)));
    }
}

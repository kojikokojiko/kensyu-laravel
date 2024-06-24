<?php
declare (strict_types=1);

namespace Tests\Unit;


use App\Models\Article;
use App\Models\Tag;
use App\Models\Thumbnail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    public function test_記事を作成できる()
    {
        $article = Article::factory()->create([
            'title' => 'Test Title',
            'body' => 'Test Body',
        ]);

        $this->assertDatabaseHas('articles', [
            'title' => 'Test Title',
            'body' => 'Test Body',
        ]);
    }

    public function test_記事とサムネイルのリレーションが機能する()
    {
        $article = Article::factory()->create();
        $thumbnail = Thumbnail::factory()->create(['article_id' => $article->id]);

        $this->assertTrue($article->thumbnail->is($thumbnail));
    }

    public function test_記事とタグのリレーションが機能する()
    {
        $article = Article::factory()->create();
        $tag = Tag::factory()->create();

        $article->tags()->attach($tag->id);

        $this->assertTrue($article->tags->contains($tag));
    }
}

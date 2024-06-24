<?php

declare (strict_types=1);

namespace Tests\Unit;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase;

    public function test_タグを作成できる()
    {
        $tag = Tag::factory()->create(['name' => 'Technology']);

        $this->assertDatabaseHas('tags', ['name' => 'Technology']);
    }

    public function test_タグと記事のリレーションが機能する()
    {
        $tag = Tag::factory()->create();
        $article = Article::factory()->create();

        $tag->articles()->attach($article->id);

        $this->assertTrue($tag->articles->contains($article));
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Article;
use App\Models\Thumbnail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThumbnailTest extends TestCase
{
    use RefreshDatabase;

    public function test_サムネイルを作成できる()
    {
        $thumbnail = Thumbnail::factory()->create([
            'path' => 'thumbnails/thumbnail.jpg',
        ]);

        $this->assertDatabaseHas('thumbnails', ['path' => 'thumbnails/thumbnail.jpg']);
    }

    public function test_サムネイルと記事のリレーションが機能する()
    {
        $article = Article::factory()->create();
        $thumbnail = Thumbnail::factory()->create(['article_id' => $article->id]);

        $this->assertTrue($thumbnail->article->is($article));
    }
}

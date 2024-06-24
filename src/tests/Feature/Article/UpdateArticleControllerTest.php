<?php

declare (strict_types=1);

namespace Feature\Article;


use App\Models\Article;
use App\Models\Tag;
use App\Models\Thumbnail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_記事が正常に更新される()
    {
        // ストレージをモック
        Storage::fake('testing');

        // テストデータの作成
        $article = Article::factory()
            ->has(Thumbnail::factory())
            ->has(Tag::factory()->count(3))
            ->create();

        // 更新データの準備
        $newTitle = 'Updated Title';
        $newBody = 'Updated Body';
        $newTags = Tag::factory()->count(2)->create()->pluck('id')->toArray();
        $newThumbnail = UploadedFile::fake()->image('new_thumbnail.jpg');

        // リクエストの実行
        $response = $this->put(route('articles.update_article', $article), [
            'title' => $newTitle,
            'body' => $newBody,
            'tags' => $newTags,
            'thumbnail' => $newThumbnail,
        ]);

        // アサーション
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success', 'Article updated successfully.');

        // 更新されたデータを再取得
        $article->refresh();

        // 記事が更新されていることを確認
        $this->assertEquals($newTitle, $article->title);
        $this->assertEquals($newBody, $article->body);

        // サムネイルが更新されていることを確認
        Storage::disk('public')->assertExists('thumbnails/' . $newThumbnail->hashName());
        $this->assertEquals('thumbnails/' . $newThumbnail->hashName(), $article->thumbnail->path);

        // タグが更新されていることを確認
        $this->assertCount(2, $article->tags);
        $this->assertEquals($newTags, $article->tags->pluck('id')->toArray());
    }

    public function test_例外発生時にロールバックされる()
    {
        // ストレージをモック
        Storage::fake('testing');
        // テストデータの作成
        $article = Article::factory()
            ->has(Thumbnail::factory())
            ->has(Tag::factory()->count(3))
            ->create();

        // 無効なデータを準備して、例外を引き起こす
        $invalidData = [
            'title' => 'Updated Title',
            'body' => 'Updated Body',
            'tags' => [9999], // 存在しないタグID
            'thumbnail' => UploadedFile::fake()->image('new_thumbnail.jpg'),
        ];

        // リクエストの実行
        $response = $this->put(route('articles.update_article', $article), $invalidData);

        // アサーション
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error', 'Failed to update article: The tags contain invalid IDs.');

        // 記事が更新されていないことを確認
        $article->refresh();
        $this->assertNotEquals('Updated Title', $article->title);
        $this->assertNotEquals('Updated Body', $article->body);

        // サムネイルが更新されていないことを確認
        Storage::disk('public')->assertMissing('thumbnails/new_thumbnail.jpg');
    }
}

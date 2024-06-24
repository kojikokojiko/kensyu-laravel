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

class CreateArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_記事が正常に作成される()
    {
        // ストレージをモック
        Storage::fake('testing');

        // テストデータの準備
        $tags = Tag::factory()->count(3)->create()->pluck('id')->toArray();
        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');
        $images = [
            UploadedFile::fake()->image('image1.jpg'),
            UploadedFile::fake()->image('image2.jpg')
        ];

        // リクエストの実行
        $response = $this->post(route('articles.create_article'), [
            'title' => 'Test Article',
            'body' => 'This is a test article.',
            'tags' => $tags,
            'thumbnail' => $thumbnail,
            'images' => $images,
        ]);

        // アサーション
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success', 'Article created successfully.');

        // 作成された記事を取得
        $article = Article::where('title', 'Test Article')->firstOrFail();

        // 記事が作成されていることを確認
        $this->assertEquals('Test Article', $article->title);
        $this->assertEquals('This is a test article.', $article->body);

        // サムネイルが保存されていることを確認
        Storage::disk('public')->assertExists('thumbnails/' . $thumbnail->hashName());
        $this->assertEquals('thumbnails/' . $thumbnail->hashName(), $article->thumbnail->path);

        // 画像が保存されていることを確認
        foreach ($images as $image) {
            Storage::disk('public')->assertExists('article_images/' . $image->hashName());
        }

        // タグが正しく関連付けられていることを確認
        $this->assertCount(3, $article->tags);
        $this->assertEquals($tags, $article->tags->pluck('id')->toArray());
    }

    public function test_例外発生時にロールバックされる()
    {
        // ストレージをモック
        Storage::fake('testing');

        // 無効なデータを準備して、例外を引き起こす
        $invalidTags = [9999]; // 存在しないタグID
        $thumbnail = UploadedFile::fake()->image('thumbnail.jpg');
        $images = [
            UploadedFile::fake()->image('image1.jpg'),
            UploadedFile::fake()->image('image2.jpg')
        ];

        // リクエストの実行
        $response = $this->post(route('articles.create_article'), [
            'title' => 'Test Article',
            'body' => 'This is a test article.',
            'tags' => $invalidTags,
            'thumbnail' => $thumbnail,
            'images' => $images,
        ]);

        // アサーション
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('error', 'Failed to create article: The tags contain invalid IDs.');

        // 記事が作成されていないことを確認
        $this->assertDatabaseMissing('articles', ['title' => 'Test Article']);

        // サムネイルが保存されていないことを確認
        Storage::disk('public')->assertMissing('thumbnails/' . $thumbnail->hashName());

        // 画像が保存されていないことを確認
        foreach ($images as $image) {
            Storage::disk('public')->assertMissing('article_images/' . $image->hashName());
        }
    }
}

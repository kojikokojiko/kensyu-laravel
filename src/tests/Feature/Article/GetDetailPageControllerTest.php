<?php

namespace Feature\Article;
use App\Models\Article;
use App\Models\Tag;
use App\Models\Thumbnail;
//use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetDetailPageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_記事詳細ページが適切に表示される()
    {
        // 特定のユーザーを作成
        $user = User::factory()->create();
        // テストデータの作成
        $article = Article::factory()
            ->for($user) // ここで記事をユーザーに関連付ける
            ->has(Thumbnail::factory())
            ->has(Tag::factory()->count(3))
//            ->has(Image::factory()->count(3))
            ->create();

        // リクエストの実行
        $response = $this->get(route('articles.get_detail_page', $article));

        // アサーション
        $response->assertStatus(200);
        $response->assertViewIs('articles.show');
        $response->assertViewHas('article', $article);

        // タイトルと本文が表示されていることを確認
        $response->assertSeeText($article->title);
        $response->assertSeeText($article->body);

        // 投稿者情報が表示されていることを確認
        $response->assertSeeText($article->user->name);
        $response->assertSee(asset('storage/' . str_replace('public/', '', $article->user->profile_image)));

        // サムネイル画像が表示されていることを確認
        if ($article->thumbnail) {
            $response->assertSee(asset('storage/' . $article->thumbnail->path));
        }

        // 本文中の画像が表示されていることを確認
        foreach ($article->images as $image) {
            $response->assertSee(asset('storage/' . $image->path));
        }

        // タグが表示されていることを確認
        foreach ($article->tags as $tag) {
            $response->assertSeeText($tag->name);
        }
    }
}

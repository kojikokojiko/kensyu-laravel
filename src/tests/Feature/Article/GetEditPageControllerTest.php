<?php

declare(strict_types=1);

namespace Feature\Article;

use App\Models\Article;
use App\Models\Tag;
use App\Models\Thumbnail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetEditPageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_編集ページが適切に表示される()
{
    // テストデータの作成
    $article = Article::factory()
        ->has(Thumbnail::factory())
        ->has(Tag::factory()->count(3))
        ->create();

    // リクエストの実行
    $response = $this->get(route('articles.get_edit_page', ['article' => $article->id]));

    // デバッグ情報の出力
    $response->dumpHeaders();
    $response->dumpSession();
    $response->dump();

    // アサーション
    $response->assertStatus(200);
    $response->assertViewIs('articles.edit');
    $response->assertViewHas('article', $article);

    // input フィールドの value 属性を確認
    $response->assertSee('value="' . $article->title . '"', false);

    // textarea のテキストを確認
    $html = $response->getContent();
    $this->assertMatchesRegularExpression('/<textarea[^>]*>' . preg_quote($article->body, '/') . '<\/textarea>/', $html);

    // タグが表示されていることを確認
    foreach ($article->tags as $tag) {
        $response->assertSeeText($tag->name);
    }
}
}

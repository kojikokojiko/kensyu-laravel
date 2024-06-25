<?php
declare(strict_types=1);

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class UpdateArticleController extends Controller
{
    public function __invoke(UpdateArticleRequest $request, Article $article)
    {
        DB::beginTransaction();
        try {
            $article->update($request->only(['title', 'body']));

            if ($request->hasFile('thumbnail')) {
                // 元のサムネイルを削除
                if ($article->thumbnail && $article->thumbnail->path) {
                    Storage::delete('public/' . $article->thumbnail->path);
                }

                $path = $request->file('thumbnail')->store('public/thumbnails');
                $article->thumbnail()->updateOrCreate(
                    ['article_id' => $article->id], // 更新条件
                    ['path' => str_replace('public/', '', $path)]
                );
            }

            if ($request->hasFile('images')) {
                // 元の画像を削除
                foreach ($article->images as $existingImage) {
                    Storage::delete('public/' . $existingImage->path);
                    $existingImage->delete(); // レコードも削除
                }

                foreach ($request->file('images') as $image) {
                    $path = $image->store('public/article_images');
                    $article->images()->create(['path' => str_replace('public/', '', $path)]);
                }
            }

            $article->tags()->sync($request->tags);
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->route('home')->with('error', 'Failed to update article: The tags contain invalid IDs.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('home')->with('error', 'Failed to update article: ' . $e->getMessage());
        }

        return redirect()->route('home')->with('success', 'Article updated successfully.');
    }
}

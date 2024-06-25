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

            // 新しいサムネイルの保存
            $oldThumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                if ($article->thumbnail && $article->thumbnail->path) {
                    $oldThumbnailPath = $article->thumbnail->path;
                }

                $thumbnailPath = $request->file('thumbnail')->store('public/thumbnails');
                $article->thumbnail()->updateOrCreate(
                    ['article_id' => $article->id], // 更新条件
                    ['path' => str_replace('public/', '', $thumbnailPath)]
                );
            }

            // 新しい画像の保存
            $oldImagePaths = [];
            if ($request->hasFile('images')) {
                $oldImagePaths = $article->images->pluck('path')->toArray();
                $article->images()->delete(); // 既存の画像を削除

                $newImagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('public/article_images');
                    $newImagePaths[] = str_replace('public/', '', $path);
                }
                $article->images()->createMany(array_map(fn($path) => ['path' => $path], $newImagePaths));
            }

            $article->tags()->sync($request->tags);
            DB::commit();

            // トランザクション成功後にファイル削除
            if ($oldThumbnailPath) {
                Storage::delete('public/' . $oldThumbnailPath);
            }

            if (!empty($oldImagePaths)) {
                Storage::delete(array_map(fn($path) => 'public/' . $path, $oldImagePaths));
            }

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

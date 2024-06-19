<?php
declare(strict_types=1);

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Support\Facades\DB;


class UpdateArticleController extends Controller
{
    public function __invoke(UpdateArticleRequest $request, Article $article)
    {
        DB::beginTransaction();
        try {
            $article->update($request->only(['title', 'body']));

            if ($request->hasFile('thumbnail')) {
                $path = $request->file('thumbnail')->store('public/thumbnails');
                $article->thumbnail()->update(['path' => str_replace('public/', '', $path)]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('home')->with('error', 'Failed to update article: ' . $e->getMessage());
        }

        return redirect()->route('home')->with('success', 'Article updated successfully.');
    }
}

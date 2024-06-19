<?php
declare(strict_types=1);

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;


class UpdateArticleController extends Controller
{
    public function __invoke(UpdateArticleRequest $request, Article $article)
    {

        $article->update($request->only(['title', 'body']));

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('public/thumbnails');
            $article->thumbnail()->create(['url' => str_replace('public/', '', $path)]);
        }

        return redirect()->route('home')->with('success', 'Article updated successfully.');
    }
}

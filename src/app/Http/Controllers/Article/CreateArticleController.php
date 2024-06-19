<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateArticleRequest;
use App\Models\Article;
use Illuminate\Http\Request;

class CreateArticleController extends Controller
{
    public function __invoke(CreateArticleRequest $request)
    {
        $article = Article::create($request->validated());

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('public/thumbnails');
            $article->thumbnail()->create(['path' => str_replace('public/', '', $path)]);
        }
        return redirect()->route('home')->with('success', 'Article created successfully.');
    }
}

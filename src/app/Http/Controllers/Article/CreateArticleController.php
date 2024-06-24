<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateArticleRequest;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreateArticleController extends Controller
{
    public function __invoke(CreateArticleRequest $request)
    {
        DB::beginTransaction();

        try {
            $article = Article::create($request->validated());
            $article->tags()->sync($request->tags);
            if ($request->hasFile('thumbnail')) {
                $path = $request->file('thumbnail')->store('public/thumbnails');
                $article->thumbnail()->create(['path' => str_replace('public/', '', $path)]);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('home')->with('error', 'Failed to create article: ' . $e->getMessage());
        }

        return redirect()->route('home')->with('success', 'Article created successfully.');
    }
}

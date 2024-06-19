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
        Article::create($request->validated());
        return redirect()->route('home')->with('success', 'Article created successfully.');
    }
}

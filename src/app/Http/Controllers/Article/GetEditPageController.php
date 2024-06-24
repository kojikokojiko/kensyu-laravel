<?php

declare(strict_types=1);

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Tag;

class GetEditPageController extends Controller
{
    public function __invoke(Article $article)
    {
        $tags = Tag::all();
        return view('articles.edit', compact('article','tags'));
    }
}

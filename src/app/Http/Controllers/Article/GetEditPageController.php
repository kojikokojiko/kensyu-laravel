<?php

declare(strict_types=1);

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Models\Article;

class GetEditPageController extends Controller
{
    public function __invoke(Article $article)
    {
        return view('articles.edit', compact('article'));
    }
}

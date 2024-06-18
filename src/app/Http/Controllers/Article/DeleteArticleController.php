<?php
declare(strict_types=1);

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Models\Article;

class DeleteArticleController extends Controller
{
    public function __invoke(Article $article)
    {
        $article->delete();
        return redirect()->route('home')->with('success', 'Article deleted successfully.');
    }
}

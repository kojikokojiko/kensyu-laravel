<?php
declare(strict_types=1);

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class UpdateArticleController extends Controller
{
    public function __invoke(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        $article->update($request->all());
        return redirect()->route('home')->with('success', 'Article updated successfully.');
    }
}

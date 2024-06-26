<?php
declare(strict_types=1);

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class DeleteArticleController extends Controller
{
    public function __invoke(Article $article)
    {
        if ($article->user_id !== Auth::id()) {
            return redirect()->route('home')->with('error', '他のユーザーの投稿は削除できません');
        }
        $article->delete();
        return redirect()->route('home')->with('success', 'Article deleted successfully.');
    }
}

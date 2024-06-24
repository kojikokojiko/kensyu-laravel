<?php
declare(strict_types=1);

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Models\Tag;

class GetCreatePageController extends Controller
{
    public function __invoke()
    {
        $tags = Tag::all();
        return view('articles.create', compact('tags'));
    }
}

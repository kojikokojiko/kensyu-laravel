<?php
declare(strict_types=1);

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;

class CreateController extends Controller
{
    public function __invoke()
    {
        return view('articles.create');
    }
}

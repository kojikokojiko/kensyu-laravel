<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:5000',
        ], [
            'title.required' => 'タイトルは必須です。',
            'title.max' => 'タイトルは255文字以内で入力してください。',
            'body.required' => '本文は必須です。',
            'body.max' => '本文は5000文字以内で入力してください。',
        ]);

        Article::create($request->all());
        return redirect()->route('home')->with('success', 'Article created successfully.');
    }
}

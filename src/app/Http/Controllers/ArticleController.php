<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    // 記事一覧表示
    public function index()
    {
        $articles = Article::all();
        return view('articles.index', compact('articles'));
    }

//     記事作成フォーム表示
    public function create()
    {
        return view('articles.create');
    }

    // 記事の保存
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        Article::create($request->all());
        return redirect()->route('articles.index')->with('success', 'Article created successfully.');
    }

    // 記事の編集フォーム表示
    public function edit(Article $article)
    {
        return view('articles.edit', compact('article'));
    }

    // 記事の更新
    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        $article->update($request->all());
        return redirect()->route('articles.index')->with('success', 'Article updated successfully.');
    }

    // 記事の削除
    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index')->with('success', 'Article deleted successfully.');
    }
}

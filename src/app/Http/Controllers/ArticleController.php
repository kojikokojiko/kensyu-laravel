<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    // 記事一覧表示
    public function index()
    {
        $articles = Article::with('user')->get(); // ユーザー情報をロード
        return view('articles.index', compact('articles'));
    }

//     記事作成フォーム表示
    public function create()
    {
        $tags = Tag::all();
        return view('articles.create', compact('tags'));
    }

    // 記事の保存
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:5000',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id'
        ], [
            'title.required' => 'タイトルは必須です。',
            'title.max' => 'タイトルは255文字以内で入力してください。',
            'body.required' => '本文は必須です。',
            'body.max' => '本文は5000文字以内で入力してください。',
            'thumbnail.image' => 'サムネイルは画像ファイルである必要があります。',
            'images.*.image' => '本文中の画像は画像ファイルである必要があります。',
            'tags.required' => '少なくとも1つのタグを選択してください。',
            'tags.*.exists' => '選択されたタグは無効です。',
        ]);

        $article = Article::create(array_merge(
            $request->only(['title', 'body']),
            ['user_id' => Auth::id()]
        ));


        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $filename = time() . '_' . $thumbnail->getClientOriginalName();
            $path = $thumbnail->storeAs('public/thumbnails', $filename);
            $article->thumbnail()->create(['url' => str_replace('public/', '', $path)]);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('public/article_images', $filename);
                $article->images()->create(['url' => str_replace('public/', '', $path)]);
            }
        }

        $article->tags()->sync($request->tags);

        return redirect()->route('home')->with('success', 'Article created successfully.');
//        Article::create($request->all());
//        return redirect()->route('articles.index')->with('success', 'Article created successfully.');
    }

    // 記事の詳細表示
    public function show(Article $article)
    {
        $article->load('tags');
        return view('articles.show', compact('article'));
    }

    // 記事の編集フォーム表示
    public function edit(Article $article)
    {
        if ($article->user_id !== Auth::id()) {
            return redirect()->route('articles.index')->withErrors('他のユーザーの投稿は、編集、更新、削除できません');
        }

        $tags = Tag::all();
        $article->load('tags');
        return view('articles.edit', compact('article', 'tags'));
    }

    // 記事の更新
    public function update(Request $request, Article $article)
    {

        if ($article->user_id !== Auth::id()) {
            return redirect()->route('articles.index')->withErrors('他のユーザーの投稿は、編集、更新、削除できません');
        }


        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:5000',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id'
        ], [
            'title.required' => 'タイトルは必須です。',
            'title.max' => 'タイトルは255文字以内で入力してください。',
            'body.required' => '本文は必須です。',
            'body.max' => '本文は5000文字以内で入力してください。',
            'thumbnail.image' => 'サムネイルは画像ファイルである必要があります。',
            'images.*.image' => '本文中の画像は画像ファイルである必要があります。',
            'tags.required' => '少なくとも1つのタグを選択してください。',
            'tags.*.exists' => '選択されたタグは無効です。',
        ]);

        $article->update($request->only(['title', 'body']));

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('public/thumbnails');
            $article->thumbnail()->create(['url' => str_replace('public/', '', $path)]);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/article_images');
                $article->images()->create(['url' => str_replace('public/', '', $path)]);
            }
        }

        $article->tags()->sync($request->tags);
        return redirect()->route('articles.index')->with('success', 'Article updated successfully.');
    }

    // 記事の削除
    public function destroy(Article $article)
    {
        if ($article->user_id !== Auth::id()) {
            return redirect()->route('articles.index')->withErrors('他のユーザーの投稿は、編集、更新、削除できません');
        }

        $article->delete();
        return redirect()->route('articles.index')->with('success', 'Article deleted successfully.');
    }
}

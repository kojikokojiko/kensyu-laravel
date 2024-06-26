<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateArticleRequest;
use App\Models\Article;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateArticleController extends Controller
{
    public function __invoke(CreateArticleRequest $request)
    {
        DB::beginTransaction();

        try {
            $article = Article::create(array_merge(
                $request->only(['title', 'body']),
                ['user_id' => Auth::id()]
            ));

            $article->tags()->sync($request->tags);
            if ($request->hasFile('thumbnail')) {
                $path = $request->file('thumbnail')->store('public/thumbnails');
                $article->thumbnail()->create(['path' => str_replace('public/', '', $path)]);
            }

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
//                    $filename = time() . '_' . $image->getClientOriginalName();
                    $path = $image->store('public/article_images');
                    $article->images()->create(['path' => str_replace('public/', '', $path)]);
                }
            }

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            return redirect()->route('home')->with('error', 'Failed to create article: The tags contain invalid IDs.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('home')->with('error', 'Failed to create article: ' . $e->getMessage());
        }

        return redirect()->route('home')->with('success', 'Article created successfully.');
    }
}

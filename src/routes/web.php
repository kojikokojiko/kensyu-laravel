<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;

//
//Route::get('/', function () {
//    return view('welcome');
//});

// ルートを / に設定する
Route::get('/', [ArticleController::class, 'index'])->name('home');
// 記事関連のリソースルート
Route::resource('articles', ArticleController::class);

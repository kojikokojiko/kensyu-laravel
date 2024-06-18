<?php

use App\Http\Controllers\Article\GetCreatePageController;
use App\Http\Controllers\Article\IndexController;
use App\Http\Controllers\Article\CreateArticleController;
use Illuminate\Support\Facades\Route;


// ホームページに記事一覧を表示
Route::get('/', IndexController::class)->name('home');
Route::get('articles/create', GetCreatePageController::class)->name('articles.create');
Route::post('articles', CreateArticleController::class)->name('articles.store');

<?php

use App\Http\Controllers\Article\GetCreatePageController;
use App\Http\Controllers\Article\GetEditPageController;
use App\Http\Controllers\Article\IndexController;
use App\Http\Controllers\Article\CreateArticleController;
use App\Http\Controllers\Article\UpdateArticleController;
use Illuminate\Support\Facades\Route;


// ホームページに記事一覧を表示
Route::get('/', IndexController::class)->name('home');
Route::get('articles/create', GetCreatePageController::class)->name('articles.get_create_page');
Route::post('articles', CreateArticleController::class)->name('articles.create_article');
Route::get('/articles/{article}/edit', GetEditPageController::class)->name('articles.get_edit_page');
Route::put('/articles/{article}', UpdateArticleController::class)->name('articles.update_article');

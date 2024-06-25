<?php

use App\Http\Controllers\Article\DeleteArticleController;
use App\Http\Controllers\Article\GetCreatePageController;
use App\Http\Controllers\Article\GetDetailPageController;
use App\Http\Controllers\Article\GetEditPageController;
use App\Http\Controllers\Article\IndexController;
use App\Http\Controllers\Article\CreateArticleController;
use App\Http\Controllers\Article\UpdateArticleController;
use App\Http\Controllers\Auth\GetRegisterPageController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;


// ホームページに記事一覧を表示
Route::get('/', IndexController::class)->name('home');
Route::get('articles/create', GetCreatePageController::class)->name('articles.get_create_page');
Route::post('articles', CreateArticleController::class)->name('articles.create_article');
Route::get('/articles/{article}/edit', GetEditPageController::class)->name('articles.get_edit_page');
Route::put('/articles/{article}', UpdateArticleController::class)->name('articles.update_article');
Route::delete('/articles/{article}', DeleteArticleController::class)->name('articles.delete_article');
Route::get('/articles/{article}', GetDetailPageController::class)->name('articles.get_detail_page');

Route::get('register', GetRegisterPageController::class)->name('register');
Route::post('register', RegisterController::class,);

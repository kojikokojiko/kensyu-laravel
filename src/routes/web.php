<?php

use App\Http\Controllers\Article\DeleteArticleController;
use App\Http\Controllers\Article\GetCreatePageController;
use App\Http\Controllers\Article\GetDetailPageController;
use App\Http\Controllers\Article\GetEditPageController;
use App\Http\Controllers\Article\IndexController;
use App\Http\Controllers\Article\CreateArticleController;
use App\Http\Controllers\Article\UpdateArticleController;
use App\Http\Controllers\Auth\GetLoginPageController;
use App\Http\Controllers\Auth\GetRegisterPageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogOutController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;


// ホームページに記事一覧を表示
Route::get('/', IndexController::class)->name('home');

// 記事作成ページ
Route::get('articles/create', GetCreatePageController::class)->name('articles.get_create_page')->middleware('auth');
Route::post('articles', CreateArticleController::class)->name('articles.create_article')->middleware('auth');

// 記事編集ページ
Route::get('/articles/{article}/edit', GetEditPageController::class)->name('articles.get_edit_page')->middleware('auth');
Route::put('/articles/{article}', UpdateArticleController::class)->name('articles.update_article')->middleware('auth');

// 記事削除
Route::delete('/articles/{article}', DeleteArticleController::class)->name('articles.delete_article')->middleware('auth');

// 記事詳細ページ
Route::get('/articles/{article}', GetDetailPageController::class)->name('articles.get_detail_page');

Route::get('register', GetRegisterPageController::class)->name('register');
Route::post('register', RegisterController::class,);

Route::get('login', GetLoginPageController::class)->name('login');
Route::post('login', LoginController::class,);
Route::post('logout', LogOutController::class,)->name('logout');

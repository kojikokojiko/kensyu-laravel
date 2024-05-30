<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;

//
//Route::get('/', function () {
//    return view('welcome');
//});

// ルートを / に設定する
Route::get('/', [ArticleController::class, 'index'])->name('home');
// 記事関連のリソースルート
// 記事関連のリソースルート
Route::resource('articles', ArticleController::class)->middleware('auth')->except(['home','show']);
Route::resource('articles', ArticleController::class)->only([ 'show']);

// ユーザー情報ページのルート
Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');

// ユーザー登録
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// ログイン
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

// ログアウト
// ログアウト
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

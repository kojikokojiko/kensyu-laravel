<?php

use App\Http\Controllers\Article\CreateController;
use App\Http\Controllers\Article\IndexController;
use App\Http\Controllers\Article\StoreController;
use Illuminate\Support\Facades\Route;


// ホームページに記事一覧を表示
Route::get('/', IndexController::class)->name('home');
Route::get('articles/create', CreateController::class)->name('articles.create');
Route::post('articles', StoreController::class)->name('articles.store');

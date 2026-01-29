<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/search', SearchController::class)->name('search');

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}', [ArticleController::class, 'show'])->name('articles.show');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return redirect('/admin');
    })->name('dashboard');
});

// Email reset routes
Route::get('/forgot-email', [\App\Http\Controllers\EmailResetController::class, 'showForgotEmailForm'])->name('email.reset.request');
Route::post('/forgot-email', [\App\Http\Controllers\EmailResetController::class, 'sendResetEmail'])->name('email.reset.send');

require __DIR__.'/settings.php';

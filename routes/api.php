<?php

use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\CategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('articles', ArticleController::class)
        ->only(['index', 'show'])
        ->names(['index' => 'api.articles.index', 'show' => 'api.articles.show']);

    Route::apiResource('categories', CategoryController::class)
        ->only(['index', 'show'])
        ->names(['index' => 'api.categories.index', 'show' => 'api.categories.show']);
});

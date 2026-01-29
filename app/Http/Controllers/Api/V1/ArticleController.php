<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $articles = Article::published()->with(['category', 'author'])->paginate(12);

        return ArticleResource::collection($articles);
    }

    public function show(Article $article): JsonResource
    {
        abort_if($article->published_at === null || $article->published_at > now(), 404);

        $article->load(['category', 'author']);

        return new ArticleResource($article);
    }
}

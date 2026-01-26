<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Inertia\Inertia;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index()
    {
        return Inertia::render('Articles/Index', [
            'articles' => Article::with('category', 'author')
                ->whereNotNull('published_at')
                ->orderBy('published_at', 'desc')
                ->paginate(12),
        ]);
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article)
    {
        return Inertia::render('Articles/Show', [
            'article' => $article->load('category', 'author'),
        ]);
    }
}

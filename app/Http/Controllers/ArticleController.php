<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index(): View
    {
        $articles = Article::with('category', 'author')
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('pages.articles.index', compact('articles'));
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article): View
    {
        $article->load('category', 'author');

        return view('pages.articles.show', compact('article'));
    }
}

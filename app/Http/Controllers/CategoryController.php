<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index(): View
    {
        $categories = Category::withCount(['articles' => function ($query) {
            $query->whereNotNull('published_at');
        }])->get();

        return view('pages.categories.index', compact('categories'));
    }

    /**
     * Display the specified category with its articles.
     */
    public function show(Category $category): View
    {
        $articles = $category->articles()
            ->with('category', 'author')
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        return view('pages.categories.show', compact('category', 'articles'));
    }
}

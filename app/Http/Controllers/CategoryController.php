<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        return Inertia::render('Categories/Index', [
            'categories' => Category::withCount(['articles' => function ($query) {
                $query->whereNotNull('published_at');
            }])->get(),
        ]);
    }

    /**
     * Display the specified category with its articles.
     */
    public function show(Category $category)
    {
        return Inertia::render('Categories/Show', [
            'category' => $category,
            'articles' => $category->articles()
                ->with('category', 'author')
                ->whereNotNull('published_at')
                ->orderBy('published_at', 'desc')
                ->paginate(12),
        ]);
    }
}

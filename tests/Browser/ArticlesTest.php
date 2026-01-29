<?php

use App\Models\Article;
use App\Models\Category;

uses()->beforeEach(function () {
    $category = Category::factory()->create();
    Article::factory()->count(3)->create([
        'category_id' => $category->id,
    ]);
});

it('displays articles list', function () {
    $page = visit('/articles');

    $page->assertSee('Articles')
        ->assertCount('.article-item', 3)
        ->assertNoJavaScriptErrors();
});

it('can view an article', function () {
    $article = Article::first();

    $page = visit('/articles');

    $page->click($article->title)
        ->assertPathIs("/articles/{$article->id}")
        ->assertSee($article->title)
        ->assertSee($article->content);
});

it('shows related category on article page', function () {
    $article = Article::with('category')->first();

    $page = visit("/articles/{$article->id}");

    $page->assertSee($article->category->name)
        ->click($article->category->name)
        ->assertPathIs("/categories/{$article->category->id}");
});

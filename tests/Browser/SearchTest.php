<?php

use App\Models\Article;

uses()->beforeEach(function () {
    Article::factory()->count(5)->create();
});

it('can perform a search', function () {
    $article = Article::first();

    $page = visit('/search');

    $page->assertSee('Search')
        ->fill('q', $article->title)
        ->press('Search')
        ->assertSee($article->title);
});

it('displays no results message for non-existent search', function () {
    $page = visit('/search');

    $page->fill('q', 'nonexistentsearchterm123456789')
        ->press('Search')
        ->assertSee('No results found');
});

it('works on mobile devices', function () {
    $page = visit('/search')->on()->mobile();

    $page->assertSee('Search')
        ->assertVisible('input[name="q"]')
        ->assertNoJavaScriptErrors();
});

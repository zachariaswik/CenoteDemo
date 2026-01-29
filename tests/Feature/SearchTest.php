<?php

use Illuminate\Support\Facades\Http;

it('requires a query', function () {
    $this->getJson(route('search'))->assertUnprocessable();
});

it('requires a valid page when provided', function () {
    $this->getJson(route('search', ['query' => 'laravel', 'page' => 0]))
        ->assertUnprocessable();
});

it('returns results from serpapi', function () {
    config(['services.serpapi.api_key' => 'test_key']);

    Http::fake([
        'serpapi.com/*' => Http::response([
            'organic_results' => [
                [
                    'title' => 'Example Result',
                    'link' => 'https://example.com',
                    'snippet' => 'Example snippet.',
                ],
            ],
        ], 200),
    ]);

    $this->getJson(route('search', ['query' => 'laravel']))
        ->assertSuccessful()
        ->assertJsonCount(1, 'results')
        ->assertJsonPath('results.0.title', 'Example Result')
        ->assertJsonPath('results.0.link', 'https://example.com')
        ->assertJsonPath('page', 1)
        ->assertJsonPath('per_page', 5)
        ->assertJsonPath('has_more', false);

    Http::assertSent(function ($request) {
        $data = $request->data();

        return str_starts_with($request->url(), 'https://serpapi.com/search.json')
            && ($data['engine'] ?? null) === 'duckduckgo'
            && ($data['q'] ?? null) === 'laravel'
            && ($data['num'] ?? null) === 5
            && ($data['start'] ?? null) === 0;
    });
});

it('returns 503 when serpapi is not configured', function () {
    config(['services.serpapi.api_key' => null]);

    $this->getJson(route('search', ['query' => 'laravel']))
        ->assertStatus(503);
});

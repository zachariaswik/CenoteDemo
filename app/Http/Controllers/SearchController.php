<?php

namespace App\Http\Controllers;

use App\Http\Requests\Search\SearchRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    /**
     * Execute a DuckDuckGo search using SerpAPI.
     */
    public function __invoke(SearchRequest $request): JsonResponse
    {
        if (! config('services.serpapi.api_key')) {
            return response()->json(['message' => 'SerpAPI not configured.'], 503);
        }

        $page = max(1, (int) $request->validated('page', 1));
        $perPage = 5;

        $response = Http::get('https://serpapi.com/search.json', [
            'engine' => 'duckduckgo',
            'q' => $request->validated('query'),
            'num' => $perPage,
            'start' => ($page - 1) * $perPage,
            'api_key' => config('services.serpapi.api_key'),
        ]);

        if (! $response->successful()) {
            return response()->json(['message' => 'Search failed.'], 502);
        }

        $results = collect($response->json('organic_results', []))
            ->map(fn (array $result): array => [
                'title' => $result['title'] ?? null,
                'link' => $result['link'] ?? null,
                'snippet' => $result['snippet'] ?? null,
            ])
            ->filter(fn (array $result): bool => filled($result['title']) && filled($result['link']))
            ->take($perPage)
            ->values();

        return response()->json([
            'page' => $page,
            'per_page' => $perPage,
            'has_more' => $results->count() === $perPage,
            'results' => $results,
        ]);
    }
}

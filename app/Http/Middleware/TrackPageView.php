<?php

namespace App\Http\Middleware;

use App\Services\PostHogService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    /**
     * Routes to exclude from page view tracking.
     *
     * @var array<int, string>
     */
    protected array $excludedPaths = [
        'admin',
        'admin/*',
        'livewire/*',
        '_debugbar/*',
        'horizon/*',
        'telescope/*',
        'sanctum/*',
        '_ignition/*',
    ];

    public function __construct(protected PostHogService $postHogService) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track GET requests (page views, not form submissions)
        if ($request->method() !== 'GET') {
            return $response;
        }

        // Skip excluded paths
        if ($this->shouldExclude($request)) {
            return $response;
        }

        // Track the page view
        $this->postHogService->pageView($request);

        // Identify authenticated users
        if (Auth::check()) {
            $this->postHogService->identify();
        }

        return $response;
    }

    /**
     * Determine if the request path should be excluded from tracking.
     */
    protected function shouldExclude(Request $request): bool
    {
        $path = $request->path();

        foreach ($this->excludedPaths as $excludedPath) {
            if ($request->is($excludedPath)) {
                return true;
            }
        }

        return false;
    }
}

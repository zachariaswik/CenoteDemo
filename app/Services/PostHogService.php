<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PostHog\PostHog;

class PostHogService
{
    /**
     * Get the distinct ID for the current user/session.
     * Uses authenticated user ID if logged in, otherwise falls back to session ID.
     */
    public function getDistinctId(): string
    {
        if (Auth::check()) {
            return (string) Auth::id();
        }

        return session()->getId();
    }

    /**
     * Capture a custom event.
     *
     * @param  array<string, mixed>  $properties
     */
    public function capture(string $event, array $properties = []): void
    {
        if (! config('services.posthog.api_key')) {
            return;
        }

        PostHog::capture([
            'distinctId' => $this->getDistinctId(),
            'event' => $event,
            'properties' => $properties,
        ]);
    }

    /**
     * Identify the current authenticated user with their profile data.
     * Links anonymous session events to the user when they log in.
     */
    public function identify(): void
    {
        if (! config('services.posthog.api_key')) {
            return;
        }

        if (! Auth::check()) {
            return;
        }

        $user = Auth::user();

        PostHog::identify([
            'distinctId' => (string) $user->id,
            '$set' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);

        // Alias the session ID to the user ID so anonymous events are linked
        PostHog::alias([
            'distinctId' => (string) $user->id,
            'alias' => session()->getId(),
        ]);
    }

    /**
     * Track a page view event.
     */
    public function pageView(Request $request): void
    {
        $this->capture('$pageview', [
            '$current_url' => $request->fullUrl(),
            'route_name' => $request->route()?->getName(),
            'method' => $request->method(),
            'referrer' => $request->header('referer'),
            'user_agent' => $request->userAgent(),
        ]);
    }
}

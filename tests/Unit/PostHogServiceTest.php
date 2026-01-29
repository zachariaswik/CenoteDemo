<?php

use App\Models\User;
use App\Services\PostHogService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    config(['services.posthog.api_key' => 'test_api_key']);
    config(['services.posthog.host' => 'https://eu.i.posthog.com']);
});

it('returns user id as distinct id when authenticated', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $service = new PostHogService;

    expect($service->getDistinctId())->toBe((string) $user->id);
});

it('returns session id as distinct id when guest', function () {
    $service = new PostHogService;
    $sessionId = session()->getId();

    expect($service->getDistinctId())->toBe($sessionId);
});

it('does not capture events when api key is not configured', function () {
    config(['services.posthog.api_key' => null]);

    $service = new PostHogService;

    // Should not throw an exception, just return silently
    $service->capture('test_event', ['property' => 'value']);

    expect(true)->toBeTrue();
});

it('does not identify when api key is not configured', function () {
    config(['services.posthog.api_key' => null]);

    $user = User::factory()->create();
    $this->actingAs($user);

    $service = new PostHogService;

    // Should not throw an exception, just return silently
    $service->identify();

    expect(true)->toBeTrue();
});

it('does not identify when user is not authenticated', function () {
    $service = new PostHogService;

    // Should not throw an exception, just return silently
    $service->identify();

    expect(true)->toBeTrue();
});

<?php

use App\Http\Middleware\TrackPageView;
use App\Models\User;
use App\Services\PostHogService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use function Pest\Laravel\mock;

beforeEach(function () {
    $this->postHogService = mock(PostHogService::class);
    $this->middleware = new TrackPageView($this->postHogService);
});

it('tracks page views for GET requests', function () {
    $request = Request::create('/articles', 'GET');
    $request->setRouteResolver(fn () => new class
    {
        public function getName(): string
        {
            return 'articles.index';
        }
    });

    $this->postHogService
        ->shouldReceive('pageView')
        ->once()
        ->with($request);

    $this->postHogService
        ->shouldReceive('identify')
        ->never();

    $this->middleware->handle($request, fn () => new Response);
});

it('does not track POST requests', function () {
    $request = Request::create('/articles', 'POST');

    $this->postHogService
        ->shouldReceive('pageView')
        ->never();

    $this->middleware->handle($request, fn () => new Response);
});

it('does not track PUT requests', function () {
    $request = Request::create('/settings/profile', 'PUT');

    $this->postHogService
        ->shouldReceive('pageView')
        ->never();

    $this->middleware->handle($request, fn () => new Response);
});

it('does not track DELETE requests', function () {
    $request = Request::create('/settings/profile', 'DELETE');

    $this->postHogService
        ->shouldReceive('pageView')
        ->never();

    $this->middleware->handle($request, fn () => new Response);
});

it('excludes admin routes from tracking', function () {
    $request = Request::create('/admin', 'GET');

    $this->postHogService
        ->shouldReceive('pageView')
        ->never();

    $this->middleware->handle($request, fn () => new Response);
});

it('excludes admin sub-routes from tracking', function () {
    $request = Request::create('/admin/articles/create', 'GET');

    $this->postHogService
        ->shouldReceive('pageView')
        ->never();

    $this->middleware->handle($request, fn () => new Response);
});

it('excludes livewire routes from tracking', function () {
    $request = Request::create('/livewire/message/some-component', 'GET');

    $this->postHogService
        ->shouldReceive('pageView')
        ->never();

    $this->middleware->handle($request, fn () => new Response);
});

it('excludes debugbar routes from tracking', function () {
    $request = Request::create('/_debugbar/assets/stylesheets', 'GET');

    $this->postHogService
        ->shouldReceive('pageView')
        ->never();

    $this->middleware->handle($request, fn () => new Response);
});

it('excludes horizon routes from tracking', function () {
    $request = Request::create('/horizon/dashboard', 'GET');

    $this->postHogService
        ->shouldReceive('pageView')
        ->never();

    $this->middleware->handle($request, fn () => new Response);
});

it('excludes telescope routes from tracking', function () {
    $request = Request::create('/telescope/requests', 'GET');

    $this->postHogService
        ->shouldReceive('pageView')
        ->never();

    $this->middleware->handle($request, fn () => new Response);
});

it('identifies authenticated users', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $request = Request::create('/articles', 'GET');
    $request->setRouteResolver(fn () => new class
    {
        public function getName(): string
        {
            return 'articles.index';
        }
    });
    $request->setUserResolver(fn () => $user);

    $this->postHogService
        ->shouldReceive('pageView')
        ->once();

    $this->postHogService
        ->shouldReceive('identify')
        ->once();

    $this->middleware->handle($request, fn () => new Response);
});

it('does not identify guests', function () {
    $request = Request::create('/articles', 'GET');
    $request->setRouteResolver(fn () => new class
    {
        public function getName(): string
        {
            return 'articles.index';
        }
    });

    $this->postHogService
        ->shouldReceive('pageView')
        ->once();

    $this->postHogService
        ->shouldReceive('identify')
        ->never();

    $this->middleware->handle($request, fn () => new Response);
});

it('returns the response from the next middleware', function () {
    $request = Request::create('/articles', 'GET');
    $request->setRouteResolver(fn () => new class
    {
        public function getName(): string
        {
            return 'articles.index';
        }
    });
    $expectedResponse = new Response('Hello World', 200);

    $this->postHogService->shouldReceive('pageView')->once();

    $response = $this->middleware->handle($request, fn () => $expectedResponse);

    expect($response)->toBe($expectedResponse);
    expect($response->getContent())->toBe('Hello World');
});

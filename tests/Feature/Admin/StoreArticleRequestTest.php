<?php

use App\Http\Requests\Admin\StoreArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

it('authorizes the request', function () {
    $request = new StoreArticleRequest;

    expect($request->authorize())->toBeTrue();
});

it('requires a title', function () {
    $request = new StoreArticleRequest;
    $validator = Validator::make(
        ['title' => ''],
        $request->rules()
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('title'))->toBeTrue();
});

it('requires a slug', function () {
    $request = new StoreArticleRequest;
    $validator = Validator::make(
        ['slug' => ''],
        $request->rules()
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('slug'))->toBeTrue();
});

it('requires a unique slug', function () {
    Article::factory()->create(['slug' => 'existing-slug']);

    $request = new StoreArticleRequest;
    $validator = Validator::make(
        ['slug' => 'existing-slug'],
        $request->rules()
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('slug'))->toBeTrue();
});

it('requires content', function () {
    $request = new StoreArticleRequest;
    $validator = Validator::make(
        ['content' => ''],
        $request->rules()
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('content'))->toBeTrue();
});

it('requires an excerpt', function () {
    $request = new StoreArticleRequest;
    $validator = Validator::make(
        ['excerpt' => ''],
        $request->rules()
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('excerpt'))->toBeTrue();
});

it('requires a valid category', function () {
    $request = new StoreArticleRequest;
    $validator = Validator::make(
        ['category_id' => 9999],
        $request->rules()
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('category_id'))->toBeTrue();
});

it('requires a valid author', function () {
    $request = new StoreArticleRequest;
    $validator = Validator::make(
        ['author_id' => 9999],
        $request->rules()
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('author_id'))->toBeTrue();
});

it('allows null published_at', function () {
    $category = Category::factory()->create();
    $author = User::factory()->create();

    $request = new StoreArticleRequest;
    $validator = Validator::make(
        [
            'title' => 'Article Title',
            'slug' => 'article-title',
            'content' => 'Content',
            'excerpt' => 'Excerpt',
            'published_at' => null,
            'category_id' => $category->id,
            'author_id' => $author->id,
        ],
        $request->rules()
    );

    expect($validator->fails())->toBeFalse();
});

it('passes validation with valid data', function () {
    $category = Category::factory()->create();
    $author = User::factory()->create();

    $request = new StoreArticleRequest;
    $validator = Validator::make(
        [
            'title' => 'Article Title',
            'slug' => 'article-title',
            'content' => 'Content',
            'excerpt' => 'Excerpt',
            'published_at' => now(),
            'category_id' => $category->id,
            'author_id' => $author->id,
        ],
        $request->rules()
    );

    expect($validator->fails())->toBeFalse();
});

it('has custom error messages', function () {
    $request = new StoreArticleRequest;

    expect($request->messages())->toHaveKey('title.required');
    expect($request->messages())->toHaveKey('slug.required');
    expect($request->messages())->toHaveKey('slug.unique');
    expect($request->messages())->toHaveKey('content.required');
    expect($request->messages())->toHaveKey('category_id.required');
    expect($request->messages())->toHaveKey('author_id.required');
});

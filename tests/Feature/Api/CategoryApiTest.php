<?php

use App\Models\Category;

it('lists all categories', function () {
    Category::factory()->count(5)->create();

    $response = $this->getJson('/api/v1/categories');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'slug',
                    'created_at',
                    'updated_at',
                ],
            ],
        ])
        ->assertJsonCount(5, 'data');
});

it('shows a single category by slug', function () {
    $category = Category::factory()->create();

    $response = $this->getJson("/api/v1/categories/{$category->slug}");

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'slug',
                'created_at',
                'updated_at',
            ],
        ])
        ->assertJsonPath('data.slug', $category->slug)
        ->assertJsonPath('data.name', $category->name);
});

it('returns 404 for non-existent category', function () {
    $response = $this->getJson('/api/v1/categories/non-existent-slug');

    $response->assertNotFound();
});

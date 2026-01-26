<?php

use App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use App\Filament\Resources\CategoryResource\Pages\EditCategory;
use App\Filament\Resources\CategoryResource\Pages\ListCategories;
use App\Models\Category;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Str;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create();
    $this->actingAs($this->admin);
});

it('can list categories', function () {
    $categories = Category::factory()->count(10)->create();

    Livewire::test(ListCategories::class)
        ->assertCanSeeTableRecords($categories);
});

it('can search categories by name', function () {
    $categories = Category::factory()->count(10)->create();
    $searchCategory = $categories->first();

    Livewire::test(ListCategories::class)
        ->searchTable($searchCategory->name)
        ->assertCanSeeTableRecords([$searchCategory]);
});

it('can search categories by slug', function () {
    $categories = Category::factory()->count(10)->create();
    $searchCategory = $categories->first();

    Livewire::test(ListCategories::class)
        ->searchTable($searchCategory->slug)
        ->assertCanSeeTableRecords([$searchCategory]);
});

it('can create a category with valid data', function () {
    $name = 'Technology';
    $slug = Str::slug($name);

    Livewire::test(CreateCategory::class)
        ->fillForm([
            'name' => $name,
            'slug' => $slug,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Category::class, [
        'name' => $name,
        'slug' => $slug,
    ]);
});

it('validates required fields on create', function () {
    Livewire::test(CreateCategory::class)
        ->fillForm([
            'name' => '',
            'slug' => '',
        ])
        ->call('create')
        ->assertHasFormErrors(['name', 'slug']);
});

it('validates unique slug on create', function () {
    $existingCategory = Category::factory()->create();

    Livewire::test(CreateCategory::class)
        ->fillForm([
            'name' => 'New Category',
            'slug' => $existingCategory->slug,
        ])
        ->call('create')
        ->assertHasFormErrors(['slug']);
});

it('can retrieve category data for editing', function () {
    $category = Category::factory()->create();

    Livewire::test(EditCategory::class, ['record' => $category->getRouteKey()])
        ->assertFormSet([
            'name' => $category->name,
            'slug' => $category->slug,
        ]);
});

it('can update a category', function () {
    $category = Category::factory()->create();
    $newName = 'Updated Category';
    $newSlug = Str::slug($newName);

    Livewire::test(EditCategory::class, ['record' => $category->getRouteKey()])
        ->fillForm([
            'name' => $newName,
            'slug' => $newSlug,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Category::class, [
        'id' => $category->id,
        'name' => $newName,
        'slug' => $newSlug,
    ]);
});

it('validates required fields on update', function () {
    $category = Category::factory()->create();

    Livewire::test(EditCategory::class, ['record' => $category->getRouteKey()])
        ->fillForm([
            'name' => '',
            'slug' => '',
        ])
        ->call('save')
        ->assertHasFormErrors(['name', 'slug']);
});

it('can delete a category', function () {
    $category = Category::factory()->create();

    Livewire::test(EditCategory::class, ['record' => $category->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertModelMissing($category);
});

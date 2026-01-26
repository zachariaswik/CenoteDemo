<?php

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Livewire\Livewire;

it('can render users index page', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(ListUsers::class)
        ->assertSuccessful();
});

it('can list users', function () {
    $user = User::factory()->create();
    $otherUsers = User::factory()->count(3)->create();

    $this->actingAs($user);

    Livewire::test(ListUsers::class)
        ->assertCanSeeTableRecords($otherUsers);
});

it('can search users by name', function () {
    $user = User::factory()->create();
    $searchableUser = User::factory()->create(['name' => 'John Doe']);
    $otherUser = User::factory()->create(['name' => 'Jane Smith']);

    $this->actingAs($user);

    Livewire::test(ListUsers::class)
        ->searchTable('John')
        ->assertCanSeeTableRecords([$searchableUser])
        ->assertCanNotSeeTableRecords([$otherUser]);
});

it('can search users by email', function () {
    $user = User::factory()->create();
    $searchableUser = User::factory()->create(['email' => 'unique@example.com']);
    $otherUser = User::factory()->create(['email' => 'different@example.com']);

    $this->actingAs($user);

    Livewire::test(ListUsers::class)
        ->searchTable('unique@example.com')
        ->assertCanSeeTableRecords([$searchableUser])
        ->assertCanNotSeeTableRecords([$otherUser]);
});

it('can filter users by email verified', function () {
    $user = User::factory()->create();
    $verifiedUser = User::factory()->create(['email_verified_at' => now()]);
    $unverifiedUser = User::factory()->create(['email_verified_at' => null]);

    $this->actingAs($user);

    Livewire::test(ListUsers::class)
        ->filterTable('email_verified_at', true)
        ->assertCanSeeTableRecords([$verifiedUser, $user])
        ->assertCanNotSeeTableRecords([$unverifiedUser]);
});

it('can render create user page', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateUser::class)
        ->assertSuccessful();
});

it('can create a user', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('users', [
        'name' => 'New User',
        'email' => 'newuser@example.com',
    ]);
});

it('validates required fields when creating user', function () {
    $this->actingAs(User::factory()->create());

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => '',
            'email' => '',
            'password' => '',
        ])
        ->call('create')
        ->assertHasFormErrors(['name', 'email', 'password']);
});

it('validates unique email when creating user', function () {
    $this->actingAs(User::factory()->create());
    $existingUser = User::factory()->create(['email' => 'existing@example.com']);

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'New User',
            'email' => 'existing@example.com',
            'password' => 'password123',
        ])
        ->call('create')
        ->assertHasFormErrors(['email']);
});

it('can render edit user page', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(EditUser::class, [
        'record' => $user->id,
    ])
        ->assertSuccessful();
});

it('can update a user', function () {
    $user = User::factory()->create();
    $userToEdit = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(EditUser::class, [
        'record' => $userToEdit->id,
    ])
        ->fillForm([
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas('users', [
        'id' => $userToEdit->id,
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);
});

it('can delete a user', function () {
    $user = User::factory()->create();
    $userToDelete = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(EditUser::class, [
        'record' => $userToDelete->id,
    ])
        ->callAction(DeleteAction::class);

    $this->assertDatabaseMissing('users', [
        'id' => $userToDelete->id,
    ]);
});

it('has correct navigation icon', function () {
    expect(UserResource::getNavigationIcon())->not->toBeNull();
});

it('uses correct model', function () {
    expect(UserResource::getModel())->toBe(User::class);
});

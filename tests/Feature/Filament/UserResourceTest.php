<?php

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Livewire\Livewire;

beforeEach(function () {
    $this->admin = User::factory()->create();
    $this->actingAs($this->admin);
});

it('can list users', function () {
    $users = User::factory()->count(5)->create();

    Livewire::test(ListUsers::class)
        ->assertCanSeeTableRecords($users);
});

it('can search users by name', function () {
    $users = User::factory()->count(10)->create();
    $searchUser = $users->first();

    Livewire::test(ListUsers::class)
        ->searchTable($searchUser->name)
        ->assertCanSeeTableRecords([$searchUser])
        ->assertCanNotSeeTableRecords($users->except($searchUser->id));
});

it('can search users by email', function () {
    $users = User::factory()->count(10)->create();
    $searchUser = $users->first();

    Livewire::test(ListUsers::class)
        ->searchTable($searchUser->email)
        ->assertCanSeeTableRecords([$searchUser]);
});

it('can create a user with valid data', function () {
    $newUser = User::factory()->make();

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => $newUser->name,
            'email' => $newUser->email,
            'password' => 'password123',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(User::class, [
        'name' => $newUser->name,
        'email' => $newUser->email,
    ]);
});

it('validates required fields on create', function () {
    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => '',
            'email' => '',
            'password' => '',
        ])
        ->call('create')
        ->assertHasFormErrors(['name', 'email', 'password']);
});

it('validates email format on create', function () {
    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => 'password123',
        ])
        ->call('create')
        ->assertHasFormErrors(['email']);
});

it('validates unique email on create', function () {
    $existingUser = User::factory()->create();

    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Test User',
            'email' => $existingUser->email,
            'password' => 'password123',
        ])
        ->call('create')
        ->assertHasFormErrors(['email']);
});

it('can retrieve user data for editing', function () {
    $user = User::factory()->create();

    Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
        ->assertFormSet([
            'name' => $user->name,
            'email' => $user->email,
        ]);
});

it('can update a user', function () {
    $user = User::factory()->create();
    $newData = User::factory()->make();

    Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
        ->fillForm([
            'name' => $newData->name,
            'email' => $newData->email,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(User::class, [
        'id' => $user->id,
        'name' => $newData->name,
        'email' => $newData->email,
    ]);
});

it('validates required fields on update', function () {
    $user = User::factory()->create();

    Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
        ->fillForm([
            'name' => '',
            'email' => '',
        ])
        ->call('save')
        ->assertHasFormErrors(['name', 'email']);
});

it('can delete a user', function () {
    $user = User::factory()->create();

    Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
        ->callAction(DeleteAction::class);

    $this->assertModelMissing($user);
});

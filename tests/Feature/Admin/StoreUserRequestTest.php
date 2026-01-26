<?php

use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

it('authorizes the request', function () {
    $request = new StoreUserRequest;

    expect($request->authorize())->toBeTrue();
});

it('requires a name', function () {
    $request = new StoreUserRequest;
    $validator = Validator::make(
        ['name' => ''],
        $request->rules()
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('name'))->toBeTrue();
});

it('requires an email', function () {
    $request = new StoreUserRequest;
    $validator = Validator::make(
        ['email' => ''],
        $request->rules()
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('email'))->toBeTrue();
});

it('requires a valid email format', function () {
    $request = new StoreUserRequest;
    $validator = Validator::make(
        ['email' => 'invalid-email'],
        $request->rules()
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('email'))->toBeTrue();
});

it('requires a unique email', function () {
    User::factory()->create(['email' => 'existing@example.com']);

    $request = new StoreUserRequest;
    $validator = Validator::make(
        ['email' => 'existing@example.com'],
        $request->rules()
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('email'))->toBeTrue();
});

it('requires a password', function () {
    $request = new StoreUserRequest;
    $validator = Validator::make(
        ['password' => ''],
        $request->rules()
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('password'))->toBeTrue();
});

it('passes validation with valid data', function () {
    $request = new StoreUserRequest;
    $validator = Validator::make(
        [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ],
        $request->rules()
    );

    expect($validator->fails())->toBeFalse();
});

it('has custom error messages', function () {
    $request = new StoreUserRequest;

    expect($request->messages())->toHaveKey('name.required');
    expect($request->messages())->toHaveKey('email.required');
    expect($request->messages())->toHaveKey('email.unique');
    expect($request->messages())->toHaveKey('password.required');
});

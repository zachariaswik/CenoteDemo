<?php

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ResetEmailNotification;
use App\Notifications\WelcomeEmailNotification;

it('sends reset email notification if user exists', function () {
    Notification::fake();
    $user = User::factory()->create(['name' => 'Test User']);

    $response = $this->post('/forgot-email', ['name' => 'Test User']);

    $response->assertSessionHas('status');
    Notification::assertSentTo($user, ResetEmailNotification::class);
});

it('does not send reset email notification if user does not exist', function () {
    Notification::fake();
    $response = $this->post('/forgot-email', ['name' => 'Nonexistent User']);
    $response->assertSessionHas('status');
    Notification::assertNothingSent();
});

it('sends welcome email notification after registration', function () {
    Notification::fake();
    $user = User::factory()->make();
    $this->post('/register', [
        'name' => $user->name,
        'email' => $user->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);
    $createdUser = User::where('email', $user->email)->first();
    Notification::assertSentTo($createdUser, WelcomeEmailNotification::class);
});

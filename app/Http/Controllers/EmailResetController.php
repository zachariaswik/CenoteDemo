<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\ResetEmailNotification;

class EmailResetController extends Controller
{
    public function showForgotEmailForm()
    {
        return view('pages.auth.forgot-email');
    }

    public function sendResetEmail(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
        ]);

        $user = User::where('name', $request->input('name'))->first();

        if ($user) {
            $user->notify(new ResetEmailNotification());
        }

        return back()->with('status', 'If a user with that name exists, a reset email has been sent.');
    }
}

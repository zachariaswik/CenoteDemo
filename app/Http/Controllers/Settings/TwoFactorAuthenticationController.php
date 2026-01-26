<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\TwoFactorAuthenticationRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Laravel\Fortify\Features;

class TwoFactorAuthenticationController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
            ? [new Middleware('password.confirm', only: ['show'])]
            : [];
    }

    /**
     * Show the user's two-factor authentication settings page.
     */
    public function show(TwoFactorAuthenticationRequest $request): View
    {
        $request->ensureStateIsValid();

        $user = $request->user();

        return view('pages.settings.two-factor', [
            'twoFactorEnabled' => $user->hasEnabledTwoFactorAuthentication(),
            'requiresConfirmation' => Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm'),
            'qrCodeSvg' => $user->two_factor_secret && ! $user->hasEnabledTwoFactorAuthentication()
                ? $user->twoFactorQrCodeSvg()
                : null,
            'setupKey' => $user->two_factor_secret && ! $user->hasEnabledTwoFactorAuthentication()
                ? decrypt($user->two_factor_secret)
                : null,
            'recoveryCodes' => $user->hasEnabledTwoFactorAuthentication()
                ? json_decode(decrypt($user->two_factor_recovery_codes), true)
                : [],
        ]);
    }
}

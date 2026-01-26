<x-layouts.settings title="Two-Factor Authentication">
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Two-Factor Authentication</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400">Manage your two-factor authentication settings</p>
        </div>

        @if ($twoFactorEnabled)
            {{-- 2FA Enabled State --}}
            <div class="flex flex-col items-start space-y-4">
                <span class="px-2 py-1 text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-md">
                    Enabled
                </span>

                <p class="text-slate-600 dark:text-slate-400">
                    With two-factor authentication enabled, you will be prompted for a secure, random pin during login,
                    which you can retrieve from the TOTP-supported application on your phone.
                </p>

                {{-- Recovery Codes Section --}}
                <div class="w-full p-4 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white mb-2">Recovery Codes</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">
                        Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two-factor device is lost.
                    </p>

                    @if (isset($recoveryCodes) && count($recoveryCodes) > 0)
                        <div class="bg-slate-100 dark:bg-slate-900 p-4 rounded-lg mb-4 font-mono text-sm">
                            @foreach ($recoveryCodes as $code)
                                <div class="text-slate-700 dark:text-slate-300">{{ $code }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('two-factor.recovery-codes') }}">
                        @csrf
                        <button
                            type="submit"
                            class="text-sm text-blue-600 dark:text-blue-400 hover:underline"
                        >
                            Regenerate recovery codes
                        </button>
                    </form>
                </div>

                {{-- Disable 2FA --}}
                <form method="POST" action="{{ route('two-factor.disable') }}">
                    @csrf
                    @method('DELETE')
                    <button
                        type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition font-medium flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Disable 2FA
                    </button>
                </form>
            </div>
        @else
            {{-- 2FA Disabled State --}}
            <div class="flex flex-col items-start space-y-4">
                <span class="px-2 py-1 text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-md">
                    Disabled
                </span>

                <p class="text-slate-600 dark:text-slate-400">
                    When you enable two-factor authentication, you will be prompted for a secure pin during login.
                    This pin can be retrieved from a TOTP-supported application on your phone.
                </p>

                <form method="POST" action="{{ route('two-factor.enable') }}">
                    @csrf
                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition font-medium flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Enable 2FA
                    </button>
                </form>
            </div>
        @endif

        {{-- Setup Modal (shown after enabling) --}}
        @if (isset($qrCodeSvg) && $qrCodeSvg)
            <div class="w-full p-6 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4">Complete Setup</h3>

                <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">
                    Scan the following QR code with your authenticator application and enter the verification code to complete setup.
                </p>

                <div class="flex justify-center mb-4 p-4 bg-white rounded-lg">
                    {!! $qrCodeSvg !!}
                </div>

                @if (isset($setupKey))
                    <div class="mb-4">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Or enter this code manually:</p>
                        <code class="block p-2 bg-slate-100 dark:bg-slate-900 rounded text-sm font-mono text-center">
                            {{ $setupKey }}
                        </code>
                    </div>
                @endif

                @if ($requiresConfirmation)
                    <form method="POST" action="{{ route('two-factor.confirm') }}" class="space-y-4">
                        @csrf
                        <div class="grid gap-2">
                            <label for="code" class="text-sm font-medium text-slate-700 dark:text-slate-300">Verification code</label>
                            <input
                                id="code"
                                type="text"
                                name="code"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                maxlength="6"
                                required
                                autofocus
                                placeholder="000000"
                                class="w-full max-w-xs px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center text-xl tracking-widest"
                            />
                            @error('code')
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition font-medium"
                        >
                            Confirm
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </div>
</x-layouts.settings>

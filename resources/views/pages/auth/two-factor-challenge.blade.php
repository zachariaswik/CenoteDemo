<x-layouts.auth title="Two-Factor Authentication" heading="Authentication Code" subheading="Enter the authentication code provided by your authenticator application.">
    <div class="space-y-6">
        {{-- Authentication Code Form --}}
        <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-4" id="code-form">
            @csrf

            <div class="grid gap-2">
                <label for="code" class="text-sm font-medium text-slate-700 dark:text-slate-300">Authentication code</label>
                <input
                    id="code"
                    type="text"
                    name="code"
                    inputmode="numeric"
                    pattern="[0-9]*"
                    maxlength="6"
                    autofocus
                    autocomplete="one-time-code"
                    placeholder="000000"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center text-2xl tracking-widest"
                />
                @error('code')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition font-medium"
            >
                Verify
            </button>
        </form>

        {{-- Recovery Code Form (hidden by default) --}}
        <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-4 hidden" id="recovery-form">
            @csrf

            <div class="grid gap-2">
                <label for="recovery_code" class="text-sm font-medium text-slate-700 dark:text-slate-300">Recovery code</label>
                <input
                    id="recovery_code"
                    type="text"
                    name="recovery_code"
                    autocomplete="off"
                    placeholder="Enter recovery code"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                @error('recovery_code')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition font-medium"
            >
                Verify
            </button>
        </form>

        {{-- Toggle between code and recovery --}}
        <div class="text-center">
            <button
                type="button"
                id="toggle-mode"
                class="text-sm text-blue-600 dark:text-blue-400 hover:underline"
            >
                Use a recovery code
            </button>
        </div>
    </div>

    <script>
        const codeForm = document.getElementById('code-form');
        const recoveryForm = document.getElementById('recovery-form');
        const toggleBtn = document.getElementById('toggle-mode');

        let showingRecovery = false;

        toggleBtn.addEventListener('click', function() {
            showingRecovery = !showingRecovery;

            if (showingRecovery) {
                codeForm.classList.add('hidden');
                recoveryForm.classList.remove('hidden');
                toggleBtn.textContent = 'Use an authentication code';
                document.getElementById('recovery_code').focus();
            } else {
                codeForm.classList.remove('hidden');
                recoveryForm.classList.add('hidden');
                toggleBtn.textContent = 'Use a recovery code';
                document.getElementById('code').focus();
            }
        });
    </script>
</x-layouts.auth>

<x-layouts.auth title="Forgot password" heading="Forgot password" subheading="Enter your email to receive a password reset link">
    {{-- Status Message --}}
    @if (session('status'))
        <div class="mb-4 text-center text-sm font-medium text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <div class="space-y-6">
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="grid gap-2">
                <label for="email" class="text-sm font-medium text-slate-700 dark:text-slate-300">Email address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="off"
                    placeholder="email@example.com"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                @error('email')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="my-6">
                <button
                    type="submit"
                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition font-medium"
                    data-test="email-password-reset-link-button"
                >
                    Email password reset link
                </button>
            </div>
        </form>

        <div class="space-x-1 text-center text-sm text-slate-600 dark:text-slate-400">
            <span>Or, return to</span>
            <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline">log in</a>
        </div>
    </div>
</x-layouts.auth>

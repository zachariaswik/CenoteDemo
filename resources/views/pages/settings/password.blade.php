<x-layouts.settings title="Password settings">
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Update password</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400">Ensure your account is using a long, random password to stay secure</p>
        </div>

        {{-- Success Message --}}
        @if (session('status') === 'password-updated')
            <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <p class="text-sm text-green-700 dark:text-green-400">Password updated successfully.</p>
            </div>
        @endif

        {{-- Password Form --}}
        <form method="POST" action="{{ route('user-password.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid gap-2">
                <label for="current_password" class="text-sm font-medium text-slate-700 dark:text-slate-300">Current password</label>
                <input
                    id="current_password"
                    type="password"
                    name="current_password"
                    required
                    autocomplete="current-password"
                    placeholder="Current password"
                    class="w-full max-w-md px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                @error('current_password')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-2">
                <label for="password" class="text-sm font-medium text-slate-700 dark:text-slate-300">New password</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="New password"
                    class="w-full max-w-md px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                @error('password')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-2">
                <label for="password_confirmation" class="text-sm font-medium text-slate-700 dark:text-slate-300">Confirm password</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm password"
                    class="w-full max-w-md px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                @error('password_confirmation')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition font-medium"
            >
                Save
            </button>
        </form>
    </div>
</x-layouts.settings>

<x-layouts.settings title="Profile settings">
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Profile information</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400">Update your name and email address</p>
        </div>

        {{-- Success Message --}}
        @if (session('status') === 'profile-updated')
            <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <p class="text-sm text-green-700 dark:text-green-400">Profile updated successfully.</p>
            </div>
        @endif

        {{-- Profile Form --}}
        <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="grid gap-2">
                <label for="name" class="text-sm font-medium text-slate-700 dark:text-slate-300">Name</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', auth()->user()->name) }}"
                    required
                    autocomplete="name"
                    placeholder="Full name"
                    class="w-full max-w-md px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                @error('name')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-2">
                <label for="email" class="text-sm font-medium text-slate-700 dark:text-slate-300">Email address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', auth()->user()->email) }}"
                    required
                    autocomplete="username"
                    placeholder="Email address"
                    class="w-full max-w-md px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                @error('email')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            @if ($mustVerifyEmail && auth()->user()->email_verified_at === null)
                <div class="text-sm text-slate-600 dark:text-slate-400">
                    Your email address is unverified.
                    <form method="POST" action="{{ route('verification.send') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-blue-600 dark:text-blue-400 hover:underline">
                            Click here to re-send the verification email.
                        </button>
                    </form>
                </div>
            @endif

            <button
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition font-medium"
            >
                Save
            </button>
        </form>

        <hr class="border-slate-200 dark:border-slate-700">

        {{-- Delete Account Section --}}
        <div>
            <h2 class="text-lg font-semibold text-red-600 dark:text-red-400">Delete account</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">
                Once your account is deleted, all of its resources and data will be permanently deleted.
            </p>

            <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                @csrf
                @method('DELETE')

                <div class="grid gap-4">
                    <div class="grid gap-2">
                        <label for="delete_password" class="text-sm font-medium text-slate-700 dark:text-slate-300">Confirm with password</label>
                        <input
                            id="delete_password"
                            type="password"
                            name="password"
                            required
                            placeholder="Your password"
                            class="w-full max-w-md px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        />
                        @error('password', 'userDeletion')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-fit px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition font-medium"
                    >
                        Delete account
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.settings>

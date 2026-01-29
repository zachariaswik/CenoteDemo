<x-layouts.auth title="Forgot email" heading="Forgot email" subheading="Enter your name to receive an email reset link">
    {{-- Status Message --}}
    @if (session('status'))
        <div class="mb-4 text-center text-sm font-medium text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <div class="space-y-6">
        <form method="POST" action="{{ route('email.reset.send') }}">
            @csrf

            <div class="grid gap-2">
                <label for="name" class="text-sm font-medium text-slate-700 dark:text-slate-300">Name</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="off"
                    placeholder="Your name"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                @error('name')
                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="my-6">
                <button
                    type="submit"
                    class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition font-medium"
                >
                    Email reset link
                </button>
            </div>
        </form>
    </div>
</x-layouts.auth>

<x-layouts.auth title="Email verification" heading="Verify email" subheading="Please verify your email address by clicking on the link we just emailed to you.">
    {{-- Status Message --}}
    @if (session('status') === 'verification-link-sent')
        <div class="mb-4 text-center text-sm font-medium text-green-600">
            A new verification link has been sent to the email address you provided during registration.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" class="space-y-6 text-center">
        @csrf

        <button
            type="submit"
            class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-white rounded-lg hover:bg-slate-200 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition font-medium"
        >
            Resend verification email
        </button>

        <form method="POST" action="{{ route('logout') }}" class="block">
            @csrf
            <button
                type="submit"
                class="text-sm text-blue-600 dark:text-blue-400 hover:underline"
            >
                Log out
            </button>
        </form>
    </form>
</x-layouts.auth>

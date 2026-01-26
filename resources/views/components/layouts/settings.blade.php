<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Settings' }} - {{ config('app.name', 'Cenote') }}</title>

        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <style>
            html { background-color: oklch(1 0 0); }
            html.dark { background-color: oklch(0.145 0 0); }
        </style>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css'])
        @livewireStyles
    </head>
    <body class="min-h-screen bg-slate-50 dark:bg-slate-900 font-sans antialiased">
        {{-- Navbar --}}
        <nav class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    {{-- Logo & Navigation --}}
                    <div class="flex items-center gap-8">
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                            Cenote
                        </a>
                        <div class="hidden md:flex items-center gap-6">
                            <a href="{{ route('articles.index') }}"
                               class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                Articles
                            </a>
                            <a href="{{ route('categories.index') }}"
                               class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                Categories
                            </a>
                        </div>
                    </div>

                    {{-- User Menu --}}
                    <div class="flex items-center gap-4">
                        <span class="text-slate-600 dark:text-slate-300">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-slate-500 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 transition">
                                Log out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        {{-- Main Content --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row gap-8">
                {{-- Sidebar Navigation --}}
                <aside class="w-full md:w-64 shrink-0">
                    <nav class="space-y-1">
                        <a href="{{ route('profile.edit') }}"
                           class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('profile.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            Profile
                        </a>
                        <a href="{{ route('user-password.edit') }}"
                           class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('user-password.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            Password
                        </a>
                        <a href="{{ route('appearance.edit') }}"
                           class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('appearance.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            Appearance
                        </a>
                        <a href="{{ route('two-factor.show') }}"
                           class="block px-4 py-2 rounded-lg transition {{ request()->routeIs('two-factor.*') ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-medium' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                            Two-Factor Auth
                        </a>
                    </nav>
                </aside>

                {{-- Content Area --}}
                <main class="flex-1 min-w-0">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @livewireScripts
    </body>
</html>

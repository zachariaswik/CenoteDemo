<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Cenote') }}</title>

        {{-- Inline script to detect system dark mode preference --}}
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
                               class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition {{ request()->routeIs('articles.*') ? 'text-blue-600 dark:text-blue-400 font-semibold' : '' }}">
                                Articles
                            </a>
                            <a href="{{ route('categories.index') }}"
                               class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition {{ request()->routeIs('categories.*') ? 'text-blue-600 dark:text-blue-400 font-semibold' : '' }}">
                                Categories
                            </a>
                        </div>
                    </div>

                    {{-- Auth Buttons --}}
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}"
                               class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                Login
                            </a>
                            <a href="{{ route('register') }}"
                               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Register
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        {{-- Main Content --}}
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Brand --}}
                    <div>
                        <span class="text-xl font-bold text-blue-600 dark:text-blue-400">Cenote</span>
                        <p class="mt-2 text-slate-600 dark:text-slate-400 text-sm">
                            A knowledge-sharing platform for educators and learners.
                        </p>
                    </div>

                    {{-- Quick Links --}}
                    <div>
                        <h3 class="font-semibold text-slate-900 dark:text-white mb-3">Quick Links</h3>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <a href="{{ route('articles.index') }}" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                    Articles
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('categories.index') }}" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                    Categories
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- Account --}}
                    <div>
                        <h3 class="font-semibold text-slate-900 dark:text-white mb-3">Account</h3>
                        <ul class="space-y-2 text-sm">
                            @auth
                                <li>
                                    <a href="{{ route('dashboard') }}" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                        Dashboard
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('login') }}" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                        Login
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('register') }}" class="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                        Register
                                    </a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-200 dark:border-slate-700 text-center text-sm text-slate-500 dark:text-slate-400">
                    © {{ date('Y') }} Cenote. All rights reserved.
                </div>
            </div>
        </footer>

        @livewireScripts
    </body>
</html>

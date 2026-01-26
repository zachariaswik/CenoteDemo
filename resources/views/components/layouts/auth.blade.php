<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Cenote') }}</title>

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
    </head>
    <body class="min-h-svh bg-slate-50 dark:bg-slate-900 font-sans antialiased">
        <div class="flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="w-full max-w-sm">
                <div class="flex flex-col gap-8">
                    {{-- Logo --}}
                    <div class="flex flex-col items-center gap-4">
                        <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium">
                            <div class="mb-1 flex h-9 w-9 items-center justify-center rounded-md">
                                <svg class="size-9 fill-current text-blue-600 dark:text-blue-400" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                                </svg>
                            </div>
                            <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">Cenote</span>
                        </a>

                        @isset($heading)
                            <div class="space-y-2 text-center">
                                <h1 class="text-xl font-medium text-slate-900 dark:text-white">{{ $heading }}</h1>
                                @isset($subheading)
                                    <p class="text-center text-sm text-slate-600 dark:text-slate-400">{{ $subheading }}</p>
                                @endisset
                            </div>
                        @endisset
                    </div>

                    {{-- Content --}}
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>

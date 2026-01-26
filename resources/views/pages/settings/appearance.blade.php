<x-layouts.settings title="Appearance settings">
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Appearance settings</h2>
            <p class="text-sm text-slate-600 dark:text-slate-400">Update your account's appearance settings</p>
        </div>

        {{-- Theme Selection --}}
        <div class="space-y-4">
            <h3 class="text-sm font-medium text-slate-700 dark:text-slate-300">Theme</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-xl">
                {{-- Light Theme --}}
                <button
                    type="button"
                    onclick="setTheme('light')"
                    class="theme-option p-4 border-2 rounded-lg text-center transition hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    data-theme="light"
                >
                    <div class="w-full h-16 bg-white border border-slate-200 rounded-md mb-2 flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Light</span>
                </button>

                {{-- Dark Theme --}}
                <button
                    type="button"
                    onclick="setTheme('dark')"
                    class="theme-option p-4 border-2 rounded-lg text-center transition hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    data-theme="dark"
                >
                    <div class="w-full h-16 bg-slate-800 border border-slate-600 rounded-md mb-2 flex items-center justify-center">
                        <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Dark</span>
                </button>

                {{-- System Theme --}}
                <button
                    type="button"
                    onclick="setTheme('system')"
                    class="theme-option p-4 border-2 rounded-lg text-center transition hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    data-theme="system"
                >
                    <div class="w-full h-16 bg-gradient-to-r from-white to-slate-800 border border-slate-300 rounded-md mb-2 flex items-center justify-center">
                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">System</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        function setTheme(theme) {
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            } else if (theme === 'light') {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                localStorage.removeItem('theme');
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
            updateActiveTheme();
        }

        function updateActiveTheme() {
            const currentTheme = localStorage.theme || 'system';
            document.querySelectorAll('.theme-option').forEach(btn => {
                const theme = btn.getAttribute('data-theme');
                if (theme === currentTheme) {
                    btn.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                    btn.classList.remove('border-slate-200', 'dark:border-slate-700');
                } else {
                    btn.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                    btn.classList.add('border-slate-200', 'dark:border-slate-700');
                }
            });
        }

        // Initialize on page load
        updateActiveTheme();
    </script>
</x-layouts.settings>

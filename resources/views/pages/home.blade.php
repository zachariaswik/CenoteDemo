<x-layouts.site title="Cenote - Knowledge Sharing Platform">
    {{-- Hero Section --}}
    <div class="text-center py-16">
        <h1 class="text-5xl font-bold text-slate-900 dark:text-white mb-4">
            Welcome to <span class="text-blue-600 dark:text-blue-400">Cenote</span>
        </h1>
        <p class="text-xl text-slate-600 dark:text-slate-400 mb-8 max-w-2xl mx-auto">
            A knowledge-sharing platform where educators and experts share insights, tutorials, and educational resources.
        </p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('articles.index') }}"
               class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                Browse Articles
            </a>
            <a href="{{ route('categories.index') }}"
               class="px-6 py-3 bg-white dark:bg-slate-800 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700 rounded-lg hover:border-blue-500 transition font-semibold">
                View Categories
            </a>
        </div>
    </div>

    {{-- Features Section --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 py-16">
        <div class="text-center p-6">
            <div class="text-4xl mb-4">📚</div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Quality Content</h3>
            <p class="text-slate-600 dark:text-slate-400">
                Curated articles from experienced educators and industry experts.
            </p>
        </div>
        <div class="text-center p-6">
            <div class="text-4xl mb-4">🏷️</div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Organized Categories</h3>
            <p class="text-slate-600 dark:text-slate-400">
                Find content easily with our well-organized category system.
            </p>
        </div>
        <div class="text-center p-6">
            <div class="text-4xl mb-4">👥</div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Community Driven</h3>
            <p class="text-slate-600 dark:text-slate-400">
                Join a community of learners and knowledge sharers.
            </p>
        </div>
    </div>

    {{-- CTA Section --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-12 text-center text-white">
        <h2 class="text-3xl font-bold mb-4">Ready to Start Learning?</h2>
        <p class="text-blue-100 mb-6 max-w-xl mx-auto">
            Join thousands of learners who are expanding their knowledge every day.
        </p>
        @guest
            <a href="{{ route('register') }}"
               class="inline-block px-6 py-3 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition font-semibold">
                Create Free Account
            </a>
        @else
            <a href="{{ route('dashboard') }}"
               class="inline-block px-6 py-3 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition font-semibold">
                Go to Dashboard
            </a>
        @endguest
    </div>
</x-layouts.site>

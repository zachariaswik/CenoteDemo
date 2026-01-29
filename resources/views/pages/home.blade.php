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

    {{-- Search Section --}}
    <div class="py-6">
        <div class="mx-auto max-w-2xl">
            <form id="serpapi-search-form" data-search-url="{{ route('search') }}" class="flex flex-col sm:flex-row gap-3">
                <input
                    id="serpapi-query"
                    name="query"
                    type="search"
                    placeholder="Search DuckDuckGo"
                    class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <button
                    type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold"
                >
                    Search
                </button>
            </form>

            <div
                id="serpapi-results"
                class="mt-6 max-h-0 opacity-0 overflow-hidden transition-[max-height,opacity] duration-300"
            >
                <p id="serpapi-status" class="text-sm text-slate-500 dark:text-slate-400"></p>
                <ul id="serpapi-results-list" class="mt-4 flex flex-col gap-3"></ul>
                <div id="serpapi-pagination" class="mt-4 hidden items-center justify-between gap-4 text-sm text-slate-600 dark:text-slate-400">
                    <button
                        id="serpapi-prev"
                        type="button"
                        class="px-3 py-1.5 rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled
                    >
                        Previous
                    </button>
                    <span id="serpapi-page" class="text-slate-500 dark:text-slate-400"></span>
                    <button
                        id="serpapi-next"
                        type="button"
                        class="px-3 py-1.5 rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled
                    >
                        Next
                    </button>
                </div>
            </div>
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

    <script>
        const form = document.getElementById('serpapi-search-form');
        const queryInput = document.getElementById('serpapi-query');
        const resultsPanel = document.getElementById('serpapi-results');
        const resultsList = document.getElementById('serpapi-results-list');
        const status = document.getElementById('serpapi-status');
        const pagination = document.getElementById('serpapi-pagination');
        const prevButton = document.getElementById('serpapi-prev');
        const nextButton = document.getElementById('serpapi-next');
        const pageLabel = document.getElementById('serpapi-page');

        let currentPage = 1;
        let hasMore = false;

        function expandResults() {
            resultsPanel.classList.remove('max-h-0', 'opacity-0');
            resultsPanel.classList.add('max-h-[900px]', 'opacity-100');
        }

        function resetResults() {
            resultsList.innerHTML = '';
            status.textContent = '';
            pagination.classList.add('hidden');
        }

        async function fetchResults(page) {
            const query = queryInput.value.trim();
            if (!query) {
                return;
            }

            currentPage = page;
            status.textContent = 'Searching...';
            expandResults();

            const searchUrl = form.dataset.searchUrl;
            const response = await fetch(`${searchUrl}?query=${encodeURIComponent(query)}&page=${page}`, {
                headers: { 'Accept': 'application/json' },
            });

            if (!response.ok) {
                status.textContent = 'Search failed. Please try again.';
                return;
            }

            const data = await response.json();
            const results = data.results ?? [];
            hasMore = Boolean(data.has_more);

            if (!results.length) {
                status.textContent = 'No results found.';
                return;
            }

            status.textContent = `${results.length} results`;
            pageLabel.textContent = `Page ${currentPage}`;
            prevButton.disabled = currentPage <= 1;
            nextButton.disabled = !hasMore;
            pagination.classList.remove('hidden');

            resultsList.innerHTML = '';
            results.forEach((result) => {
                const item = document.createElement('li');
                item.className = 'rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4';

                item.innerHTML = `
                    <a href="${result.link}" target="_blank" rel="noopener noreferrer"
                       class="text-blue-600 dark:text-blue-400 font-semibold hover:underline">
                        ${result.title}
                    </a>
                    ${result.snippet ? `<p class="mt-2 text-sm text-slate-600 dark:text-slate-400">${result.snippet}</p>` : ''}
                `;

                resultsList.appendChild(item);
            });
        }

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            await fetchResults(1);
        });

        prevButton.setAttribute('type', 'button');
        nextButton.setAttribute('type', 'button');

        prevButton.addEventListener('click', async (event) => {
            event.preventDefault();
            if (currentPage > 1) {
                await fetchResults(currentPage - 1);
            }
        });

        nextButton.addEventListener('click', async (event) => {
            event.preventDefault();
            if (hasMore) {
                await fetchResults(currentPage + 1);
            }
        });
    </script>
</x-layouts.site>

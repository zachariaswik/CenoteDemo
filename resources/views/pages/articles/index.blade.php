<x-layouts.site title="Articles - Cenote">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">
            Articles
        </h1>
        <p class="text-slate-600 dark:text-slate-400">
            Discover {{ $articles->total() }} educational articles from our community of teachers and experts.
        </p>
    </div>

    {{-- Articles Grid --}}
    @if($articles->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @foreach($articles as $article)
                <a href="{{ route('articles.show', $article) }}"
                   class="group block bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden hover:border-blue-500 dark:hover:border-blue-500 hover:shadow-lg dark:hover:shadow-blue-900/20 transition">
                    <div class="p-6">
                        {{-- Category Badge --}}
                        <div class="mb-3">
                            <span class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-sm font-semibold rounded-full">
                                {{ $article->category->name }}
                            </span>
                        </div>

                        {{-- Title --}}
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition line-clamp-2">
                            {{ $article->title }}
                        </h3>

                        {{-- Excerpt --}}
                        <p class="text-slate-600 dark:text-slate-400 text-sm mb-4 line-clamp-3">
                            {{ $article->excerpt }}
                        </p>

                        {{-- Meta --}}
                        <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-500">
                            <span>By {{ $article->author->name }}</span>
                            <span>{{ $article->published_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="flex justify-center">
            {{ $articles->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-slate-600 dark:text-slate-400 text-lg">
                No articles available yet.
            </p>
        </div>
    @endif
</x-layouts.site>

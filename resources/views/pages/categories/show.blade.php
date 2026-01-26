<x-layouts.site title="{{ $category->name }} - Cenote">
    <div class="mb-8">
        {{-- Breadcrumb --}}
        <nav class="mb-4 text-sm">
            <ol class="flex items-center gap-2 text-slate-500 dark:text-slate-400">
                <li><a href="{{ route('categories.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Categories</a></li>
                <li>/</li>
                <li class="text-slate-900 dark:text-white">{{ $category->name }}</li>
            </ol>
        </nav>

        <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">
            {{ $category->name }}
        </h1>
        <p class="text-slate-600 dark:text-slate-400">
            {{ $articles->total() }} article{{ $articles->total() !== 1 ? 's' : '' }} in this category.
        </p>
    </div>

    {{-- Articles Grid --}}
    @if($articles->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @foreach($articles as $article)
                <a href="{{ route('articles.show', $article) }}"
                   class="group block bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden hover:border-blue-500 dark:hover:border-blue-500 hover:shadow-lg dark:hover:shadow-blue-900/20 transition">
                    <div class="p-6">
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
                No articles in this category yet.
            </p>
        </div>
    @endif

    {{-- Back Link --}}
    <div class="mt-8">
        <a href="{{ route('categories.index') }}"
           class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
            ← Back to Categories
        </a>
    </div>
</x-layouts.site>

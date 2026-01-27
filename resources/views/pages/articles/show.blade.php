<x-layouts.site title="{{ $article->title }} - Cenote">
    <div class="max-w-4xl mx-auto">
        {{-- Breadcrumb --}}
        <nav class="mb-8 text-sm">
            <ol class="flex items-center gap-2 text-slate-500 dark:text-slate-400">
                <li><a href="{{ route('articles.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition">Articles</a></li>
                <li>/</li>
                <li class="text-slate-900 dark:text-white">{{ Str::limit($article->title, 50) }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <article class="lg:col-span-2">
                {{-- Category Badge --}}
                @if($article->category)
                    <div class="mb-4">
                        <a href="{{ route('categories.show', $article->category) }}"
                           class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-sm font-semibold rounded-full hover:bg-blue-200 dark:hover:bg-blue-900/50 transition">
                            {{ $article->category->name }}
                        </a>
                    </div>
                @endif

                {{-- Title --}}
                <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-4">
                    {{ $article->title }}
                </h1>

                {{-- Meta --}}
                <div class="flex items-center gap-4 text-sm text-slate-500 dark:text-slate-400 mb-8">
                    <span>By {{ $article->author?->name ?? 'Unknown' }}</span>
                    <span>•</span>
                    <span>{{ $article->published_at?->format('F d, Y') ?? '' }}</span>
                </div>

                {{-- Content --}}
                <div class="prose prose-slate dark:prose-invert max-w-none">
                    {!! nl2br(e($article->content)) !!}
                </div>
            </article>

            {{-- Sidebar --}}
            <aside class="lg:col-span-1">
                {{-- Author Card --}}
                @if($article->author)
                    <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 p-6 mb-6">
                        <h3 class="font-semibold text-slate-900 dark:text-white mb-4">About the Author</h3>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center text-blue-700 dark:text-blue-400 font-bold text-lg">
                                {{ strtoupper(substr($article->author->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-slate-900 dark:text-white">{{ $article->author->name }}</p>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Author</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Category Card --}}
                @if($article->category)
                    <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 p-6">
                        <h3 class="font-semibold text-slate-900 dark:text-white mb-4">Category</h3>
                        <a href="{{ route('categories.show', $article->category) }}"
                           class="block p-4 bg-slate-50 dark:bg-slate-700/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                            <span class="font-medium text-slate-900 dark:text-white">{{ $article->category->name }}</span>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                Explore more articles in this category
                            </p>
                        </a>
                    </div>
                @endif
            </aside>
        </div>

        {{-- Back Link --}}
        <div class="mt-12 pt-8 border-t border-slate-200 dark:border-slate-700">
            <a href="{{ route('articles.index') }}"
               class="inline-flex items-center gap-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                ← Back to Articles
            </a>
        </div>
    </div>
</x-layouts.site>

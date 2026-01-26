<x-layouts.site title="Categories - Cenote">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">
            Categories
        </h1>
        <p class="text-slate-600 dark:text-slate-400">
            Browse articles organized by {{ $categories->count() }} subject categories.
        </p>
    </div>

    {{-- Categories Grid --}}
    @if($categories->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category) }}"
                   class="group block bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden hover:border-blue-500 dark:hover:border-blue-500 hover:shadow-lg dark:hover:shadow-blue-900/20 transition p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                                {{ $category->name }}
                            </h3>
                        </div>
                        <div class="text-3xl">📖</div>
                    </div>

                    <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                        <span class="text-sm">
                            {{ $category->articles_count }} article{{ $category->articles_count !== 1 ? 's' : '' }}
                        </span>
                        <span class="text-slate-300 dark:text-slate-600">→</span>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-slate-600 dark:text-slate-400 text-lg">
                No categories available yet.
            </p>
        </div>
    @endif
</x-layouts.site>

import { Link } from '@inertiajs/react'
import SiteLayout from '@/layouts/SiteLayout'

interface Category {
    id: number
    name: string
    slug: string
    articles_count?: number
}

interface CategoriesIndexProps {
    categories: Category[]
}

export default function CategoriesIndex({ categories }: CategoriesIndexProps) {
    return (
        <SiteLayout>
            <div className="mb-8">
                <h1 className="text-4xl font-bold text-slate-900 dark:text-white mb-2">
                    Categories
                </h1>
                <p className="text-slate-600 dark:text-slate-400">
                    Browse articles organized by {categories.length} subject categories.
                </p>
            </div>

            {/* Categories Grid */}
            {categories.length > 0 ? (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    {categories.map((category) => (
                        <Link
                            key={category.id}
                            href={`/categories/${category.slug}`}
                            className="group block bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden hover:border-blue-500 dark:hover:border-blue-500 hover:shadow-lg dark:hover:shadow-blue-900/20 transition p-6"
                        >
                            <div className="flex items-start justify-between mb-4">
                                <div>
                                    <h3 className="text-2xl font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                                        {category.name}
                                    </h3>
                                </div>
                                <div className="text-3xl">📖</div>
                            </div>

                            <div className="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                                <span className="text-sm">
                                    {category.articles_count || 0} article{(category.articles_count || 0) !== 1 ? 's' : ''}
                                </span>
                                <span className="text-slate-300 dark:text-slate-600">→</span>
                            </div>
                        </Link>
                    ))}
                </div>
            ) : (
                <div className="text-center py-12">
                    <p className="text-slate-600 dark:text-slate-400 text-lg">
                        No categories available yet.
                    </p>
                </div>
            )}
        </SiteLayout>
    )
}

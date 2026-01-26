import { Link } from '@inertiajs/react'
import SiteLayout from '@/layouts/SiteLayout'

interface Article {
    id: number
    title: string
    excerpt: string
    slug: string
    published_at: string
    category: {
        id: number
        name: string
        slug: string
    }
    author: {
        id: number
        name: string
    }
}

interface Category {
    id: number
    name: string
    slug: string
}

interface PaginationLink {
    url: string | null
    label: string
    active: boolean
}

interface CategoriesShowProps {
    category: Category
    articles: {
        data: Article[]
        links: PaginationLink[]
        meta: {
            current_page: number
            last_page: number
            per_page: number
            total: number
        }
    }
}

export default function CategoriesShow({ category, articles }: CategoriesShowProps) {
    const { data = [], meta = { current_page: 1, last_page: 1, per_page: 12, total: 0 }, links = [] } = articles || {}

    return (
        <SiteLayout>
            {/* Breadcrumb */}
            <div className="mb-8 flex gap-2 text-sm">
                <Link href="/categories" className="text-blue-600 dark:text-blue-400 hover:text-blue-700">
                    Categories
                </Link>
                <span className="text-slate-400">/</span>
                <span className="text-slate-600 dark:text-slate-400">{category.name}</span>
            </div>

            {/* Header */}
            <div className="mb-12">
                <h1 className="text-4xl md:text-5xl font-bold text-slate-900 dark:text-white mb-4">
                    {category.name}
                </h1>
                <p className="text-lg text-slate-600 dark:text-slate-400">
                    {meta.total} article{meta.total !== 1 ? 's' : ''} in this category
                </p>
            </div>

            {/* Articles List */}
            {data.length > 0 ? (
                <div className="space-y-4 mb-12">
                    {data.map((article) => (
                        article && article.author && (
                        <Link
                            key={article.id}
                            href={`/articles/${article.slug}`}
                            className="group block bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden hover:border-blue-500 dark:hover:border-blue-500 hover:shadow-lg dark:hover:shadow-blue-900/20 transition p-6"
                        >
                            <div className="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                <div className="flex-grow">
                                    <h3 className="text-xl font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition mb-2 line-clamp-2">
                                        {article.title}
                                    </h3>

                                    <p className="text-slate-600 dark:text-slate-400 text-sm mb-4 line-clamp-2">
                                        {article.excerpt}
                                    </p>

                                    <div className="flex items-center gap-4 text-xs text-slate-500 dark:text-slate-500">
                                        <span>By {article.author.name}</span>
                                        <span>
                                            {new Date(article.published_at).toLocaleDateString('en-US', {
                                                year: 'numeric',
                                                month: 'short',
                                                day: 'numeric',
                                            })}
                                        </span>
                                    </div>
                                </div>

                                <div className="text-blue-600 dark:text-blue-400 font-semibold text-sm group-hover:translate-x-1 transition flex-shrink-0">
                                    Read →
                                </div>
                            </div>
                        </Link>
                        )
                    ))}
                </div>
            ) : (
                <div className="bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 p-12 text-center">
                    <p className="text-slate-600 dark:text-slate-400 text-lg">
                        No articles in this category yet.
                    </p>
                </div>
            )}

            {/* Pagination */}
            {meta.last_page > 1 && (
                <div className="flex justify-center gap-2">
                    {links.map((link: PaginationLink, index: number) => (
                        <Link
                            key={index}
                            href={link.url || '#'}
                            className={`px-4 py-2 rounded-lg transition ${
                                link.active
                                    ? 'bg-blue-600 text-white'
                                    : link.url
                                      ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700 hover:border-blue-500'
                                      : 'bg-slate-100 dark:bg-slate-700 text-slate-400 cursor-not-allowed'
                            }`}
                            dangerouslySetInnerHTML={{ __html: link.label }}
                        />
                    ))}
                </div>
            )}

            {/* Back Link */}
            <div className="mt-8 text-center">
                <Link
                    href="/categories"
                    className="inline-block text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 font-semibold"
                >
                    ← Back to All Categories
                </Link>
            </div>
        </SiteLayout>
    )
}

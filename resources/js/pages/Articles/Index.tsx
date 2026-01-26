import SiteLayout from '@/layouts/SiteLayout'
import { Link } from '@inertiajs/react'

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

interface ArticlesIndexProps {
    articles: {
        data: Article[]
        links: any
        meta: {
            current_page: number
            last_page: number
            per_page: number
            total: number
        }
    }
}

export default function ArticlesIndex({ articles }: ArticlesIndexProps) {
    const { data = [], meta = { current_page: 1, last_page: 1, per_page: 12, total: 0 }, links = [] } = articles || {}

    return (
        <SiteLayout>
            <div className="mb-8">
                <h1 className="text-4xl font-bold text-slate-900 dark:text-white mb-2">
                    Articles
                </h1>
                <p className="text-slate-600 dark:text-slate-400">
                    Discover {meta.total} educational articles from our community of teachers and experts.
                </p>
            </div>

            {/* Articles Grid */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                {data.map((article) => (
                    article && article.category && article.author && (
                    <Link
                        key={article.id}
                        href={`/articles/${article.slug}`}
                        className="group block bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden hover:border-blue-500 dark:hover:border-blue-500 hover:shadow-lg dark:hover:shadow-blue-900/20 transition"
                    >
                        <div className="p-6">
                            {/* Category Badge */}
                            <div className="mb-3">
                                <span className="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-sm font-semibold rounded-full">
                                    {article.category.name}
                                </span>
                            </div>

                            {/* Title */}
                            <h3 className="text-xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition line-clamp-2">
                                {article.title}
                            </h3>

                            {/* Excerpt */}
                            <p className="text-slate-600 dark:text-slate-400 text-sm mb-4 line-clamp-3">
                                {article.excerpt}
                            </p>

                            {/* Meta */}
                            <div className="flex items-center justify-between text-xs text-slate-500 dark:text-slate-500">
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
                    </Link>
                    )
                ))}
            </div>

            {/* Pagination */}
            {meta.last_page > 1 && (
                <div className="flex justify-center gap-2">
                    {links.map((link: any, index: number) => (
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
        </SiteLayout>
    )
}

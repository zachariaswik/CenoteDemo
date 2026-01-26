import { Link } from '@inertiajs/react'
import SiteLayout from '@/layouts/SiteLayout'

interface Article {
    id: number
    title: string
    content: string
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

interface ArticleShowProps {
    article: Article
}

export default function ArticleShow({ article }: ArticleShowProps) {
    return (
        <SiteLayout>
            {/* Breadcrumb */}
            <div className="mb-8 flex gap-2 text-sm">
                <Link href="/articles" className="text-blue-600 dark:text-blue-400 hover:text-blue-700">
                    Articles
                </Link>
                <span className="text-slate-400">/</span>
                <span className="text-slate-600 dark:text-slate-400">{article.title}</span>
            </div>

            {/* Header */}
            <div className="mb-8">
                <div className="mb-4">
                    <Link
                        href={`/categories/${article.category.slug}`}
                        className="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-sm font-semibold rounded-full hover:bg-blue-200 dark:hover:bg-blue-900/50 transition"
                    >
                        {article.category.name}
                    </Link>
                </div>

                <h1 className="text-4xl md:text-5xl font-bold text-slate-900 dark:text-white mb-4">
                    {article.title}
                </h1>

                <div className="flex flex-col md:flex-row md:items-center md:justify-between text-slate-600 dark:text-slate-400">
                    <div className="flex gap-4 mb-4 md:mb-0">
                        <span>By <strong>{article.author.name}</strong></span>
                        <span>
                            {new Date(article.published_at).toLocaleDateString('en-US', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                            })}
                        </span>
                    </div>
                </div>
            </div>

            {/* Content */}
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {/* Main Content */}
                <div className="lg:col-span-2">
                    <div className="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 p-8 mb-8">
                        {/* Excerpt */}
                        <div className="text-lg text-slate-700 dark:text-slate-300 font-semibold mb-6 pb-6 border-b border-slate-200 dark:border-slate-700">
                            {article.excerpt}
                        </div>

                        {/* Content */}
                        <div className="prose dark:prose-invert max-w-none">
                            <div
                                className="text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap"
                                dangerouslySetInnerHTML={{
                                    __html: article.content.replace(/\n/g, '<br />'),
                                }}
                            />
                        </div>
                    </div>

                    {/* Related Actions */}
                    <div className="flex gap-4">
                        <Link
                            href="/articles"
                            className="px-6 py-3 bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 transition font-semibold"
                        >
                            ← Back to Articles
                        </Link>
                    </div>
                </div>

                {/* Sidebar */}
                <div className="lg:col-span-1">
                    {/* Author Card */}
                    <div className="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 p-6 mb-6">
                        <h3 className="font-bold text-slate-900 dark:text-white mb-2">About the Author</h3>
                        <div className="flex items-center gap-4 mb-4">
                            <div className="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center">
                                <span className="text-white font-bold">
                                    {article.author.name.charAt(0)}
                                </span>
                            </div>
                            <div>
                                <p className="font-semibold text-slate-900 dark:text-white">
                                    {article.author.name}
                                </p>
                                <p className="text-sm text-slate-500">Content Creator</p>
                            </div>
                        </div>
                    </div>

                    {/* Category Info */}
                    <div className="bg-white dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 p-6">
                        <h3 className="font-bold text-slate-900 dark:text-white mb-4">Category</h3>
                        <Link
                            href={`/categories/${article.category.slug}`}
                            className="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition font-semibold"
                        >
                            {article.category.name} →
                        </Link>
                        <p className="text-sm text-slate-600 dark:text-slate-400 mt-4">
                            Explore more articles in this category.
                        </p>
                    </div>
                </div>
            </div>
        </SiteLayout>
    )
}

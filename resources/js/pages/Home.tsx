import { Link } from '@inertiajs/react'
import SiteLayout from '@/layouts/SiteLayout'

export default function Home() {
    return (
        <SiteLayout>
            {/* Hero Section */}
            <div className="text-center mb-16">
                <h1 className="text-4xl md:text-6xl font-bold text-slate-900 dark:text-white mb-4">
                    Welcome to Cenote
                </h1>
                <p className="text-xl text-slate-600 dark:text-slate-400 mb-8 max-w-2xl mx-auto">
                    A distraction-free learning environment where students and teachers collaborate on educational content.
                </p>
                <div className="flex gap-4 justify-center">
                    <Link
                        href="/articles"
                        className="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold"
                    >
                        Explore Articles
                    </Link>
                    <Link
                        href="/categories"
                        className="px-6 py-3 border border-blue-600 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-slate-800 transition font-semibold"
                    >
                        Browse Categories
                    </Link>
                </div>
            </div>

            {/* Features Section */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <div className="p-8 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                    <div className="text-4xl mb-4">📚</div>
                    <h3 className="text-xl font-bold text-slate-900 dark:text-white mb-2">
                        Rich Content
                    </h3>
                    <p className="text-slate-600 dark:text-slate-400">
                        Access thousands of articles organized by subject categories and learning paths.
                    </p>
                </div>

                <div className="p-8 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                    <div className="text-4xl mb-4">🎯</div>
                    <h3 className="text-xl font-bold text-slate-900 dark:text-white mb-2">
                        Focused Learning
                    </h3>
                    <p className="text-slate-600 dark:text-slate-400">
                        Distraction-free interface designed for deep learning and knowledge retention.
                    </p>
                </div>

                <div className="p-8 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                    <div className="text-4xl mb-4">👥</div>
                    <h3 className="text-xl font-bold text-slate-900 dark:text-white mb-2">
                        Community
                    </h3>
                    <p className="text-slate-600 dark:text-slate-400">
                        Connect with educators and learners around the world on your educational journey.
                    </p>
                </div>
            </div>

            {/* CTA Section */}
            <div className="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg p-8 text-center text-white">
                <h2 className="text-3xl font-bold mb-4">Ready to start learning?</h2>
                <p className="text-blue-100 mb-6 max-w-2xl mx-auto">
                    Join thousands of students discovering new knowledge every day.
                </p>
                <Link
                    href="/register"
                    className="inline-block px-8 py-3 bg-white text-blue-600 rounded-lg hover:bg-blue-50 transition font-semibold"
                >
                    Get Started Free
                </Link>
            </div>
        </SiteLayout>
    )
}

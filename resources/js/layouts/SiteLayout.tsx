import { ReactNode } from 'react'
import { Link } from '@inertiajs/react'
import { usePage } from '@inertiajs/react'

interface SiteLayoutProps {
    children: ReactNode
}

export default function SiteLayout({ children }: SiteLayoutProps) {
    const { auth } = usePage().props as any

    return (
        <div className="flex flex-col min-h-screen bg-white dark:bg-slate-950">
            {/* Navbar */}
            <nav className="border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center h-16">
                        {/* Logo */}
                        <Link
                            href="/"
                            className="text-2xl font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300"
                        >
                            Cenote
                        </Link>

                        {/* Navigation Links */}
                        <div className="hidden md:flex gap-8">
                            <Link
                                href="/articles"
                                className="text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition"
                            >
                                Articles
                            </Link>
                            <Link
                                href="/categories"
                                className="text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition"
                            >
                                Categories
                            </Link>
                        </div>

                        {/* Auth Buttons */}
                        <div className="flex gap-4">
                            {auth?.user ? (
                                <div className="flex items-center gap-4">
                                    <span className="text-slate-700 dark:text-slate-300">
                                        {auth.user.name}
                                    </span>
                                    <Link
                                        href="/dashboard"
                                        className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                                    >
                                        Dashboard
                                    </Link>
                                </div>
                            ) : (
                                <>
                                    <Link
                                        href="/login"
                                        className="px-4 py-2 text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition"
                                    >
                                        Login
                                    </Link>
                                    <Link
                                        href="/register"
                                        className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                                    >
                                        Register
                                    </Link>
                                </>
                            )}
                        </div>
                    </div>
                </div>
            </nav>

            {/* Main Content */}
            <main className="flex-grow">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    {children}
                </div>
            </main>

            {/* Footer */}
            <footer className="border-t border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900 mt-12">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div>
                            <h3 className="text-lg font-bold text-slate-900 dark:text-white mb-4">
                                Cenote
                            </h3>
                            <p className="text-slate-600 dark:text-slate-400">
                                A distraction-free learning environment for students and teachers.
                            </p>
                        </div>
                        <div>
                            <h4 className="font-semibold text-slate-900 dark:text-white mb-4">
                                Content
                            </h4>
                            <ul className="space-y-2">
                                <li>
                                    <Link
                                        href="/articles"
                                        className="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400"
                                    >
                                        Articles
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="/categories"
                                        className="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400"
                                    >
                                        Categories
                                    </Link>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h4 className="font-semibold text-slate-900 dark:text-white mb-4">
                                Resources
                            </h4>
                            <ul className="space-y-2">
                                <li>
                                    <a
                                        href="#"
                                        className="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400"
                                    >
                                        Documentation
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        className="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400"
                                    >
                                        Support
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h4 className="font-semibold text-slate-900 dark:text-white mb-4">
                                Legal
                            </h4>
                            <ul className="space-y-2">
                                <li>
                                    <a
                                        href="#"
                                        className="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400"
                                    >
                                        Privacy
                                    </a>
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        className="text-slate-600 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400"
                                    >
                                        Terms
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div className="border-t border-slate-200 dark:border-slate-800 mt-8 pt-8">
                        <p className="text-center text-slate-600 dark:text-slate-400">
                            &copy; 2026 Cenote. All rights reserved.
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    )
}

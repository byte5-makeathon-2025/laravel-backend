<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>byte5 Laravel x Next.JS Makeathon</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-byte5-cyan to-byte5-dark min-h-screen flex items-center justify-center p-8">
    <div class="bg-white rounded-3xl shadow-2xl max-w-5xl w-full overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-byte5-cyan to-byte5-dark p-12 text-center text-white">
            <img src="{{ asset('images/byte5-logo-cyan.svg') }}" alt="byte5 Logo" class="max-w-[200px] h-auto mx-auto mb-8 brightness-0 invert">
            <h1 class="text-4xl md:text-5xl font-bold mb-3 leading-tight">Laravel x Next.JS Makeathon</h1>
            <p class="text-lg md:text-xl opacity-95">Build something amazing with modern web technologies</p>
        </div>

        <!-- Content -->
        <div class="p-8 md:p-12">
            <!-- Welcome Text -->
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-semibold text-gray-900 mb-4">Welcome to the byte5 Makeathon!</h2>
                <p class="text-lg md:text-xl text-gray-600 leading-relaxed max-w-3xl mx-auto">
                    This is your Laravel backend API, ready to power your Next.JS frontend.
                    We've set up everything you need to start building incredible features right away.
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                <div class="bg-gray-50 p-6 rounded-xl border-2 border-gray-200 hover:border-byte5-cyan hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">🚀 Ready to Go</h3>
                    <p class="text-gray-600 leading-relaxed">Authentication, database, and API endpoints are all configured and ready for your creative ideas.</p>
                </div>

                <div class="bg-gray-50 p-6 rounded-xl border-2 border-gray-200 hover:border-byte5-cyan hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">🔄 Preview Environments</h3>
                    <p class="text-gray-600 leading-relaxed">Every PR gets its own preview environment with Laravel Cloud - automatically migrated and seeded.</p>
                </div>

                <div class="bg-gray-50 p-6 rounded-xl border-2 border-gray-200 hover:border-byte5-cyan hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">⚡ Modern Stack</h3>
                    <p class="text-gray-600 leading-relaxed">Laravel 12 backend paired with Next.JS frontend - the perfect combination for rapid development.</p>
                </div>

                <div class="bg-gray-50 p-6 rounded-xl border-2 border-gray-200 hover:border-byte5-cyan hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">🎯 Your Canvas</h3>
                    <p class="text-gray-600 leading-relaxed">This is your starting point. Extend, modify, and build features that bring your vision to life.</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center p-8 bg-gray-50 text-gray-500 text-sm">
            <p>
                Powered by <a href="https://www.byte5.de" target="_blank" class="text-byte5-cyan font-medium hover:underline">byte5</a> |
                Built with <a href="https://laravel.com" target="_blank" class="text-byte5-cyan font-medium hover:underline">Laravel</a> |
                Check out the <a href="/api/documentation" target="_blank" class="text-byte5-cyan font-medium hover:underline">API Docs</a> |
                GitHub Repo <a href="https://github.com/byte5-makeathon-2025/laravel-backend" target="_blank" class="text-byte5-cyan font-medium hover:underline">byte5-makeathon-2025/laravel-backend</a>
            </p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wish Submitted Successfully! - Santa's Workshop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .santa-float {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes sparkle {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        .sparkle {
            animation: sparkle 2s ease-in-out infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .bounce {
            animation: bounce 2s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-red-50 via-green-50 to-blue-50 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full overflow-hidden">
        <!-- Header with Santa -->
        <div class="bg-gradient-to-r from-red-600 via-red-500 to-red-600 p-8 md:p-12 text-center text-white relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full opacity-10">
                <div class="sparkle text-6xl absolute top-4 left-10">❄️</div>
                <div class="sparkle text-4xl absolute top-8 right-20" style="animation-delay: 0.5s">✨</div>
                <div class="sparkle text-5xl absolute bottom-10 left-20" style="animation-delay: 1s">⭐</div>
                <div class="sparkle text-4xl absolute bottom-6 right-10" style="animation-delay: 1.5s">🎄</div>
            </div>
            <div class="relative z-10">
                <div class="santa-float text-8xl mb-4">🎅</div>
                <h1 class="text-4xl md:text-5xl font-bold mb-3 leading-tight">Ho Ho Ho!</h1>
                <p class="text-lg md:text-xl opacity-95">Your Wish Has Been Received!</p>
            </div>
        </div>

        <!-- Content -->
        <div class="p-8 md:p-12">
            <!-- Success Message -->
            <div class="text-center mb-8">
                <div class="bounce text-6xl mb-4">🎉</div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Thank You, <span class="text-red-600">{{ $wish->name }}</span>!
                </h2>
                <p class="text-lg md:text-xl text-gray-700 leading-relaxed mb-6">
                    Your wish about <span class="font-semibold text-green-600">"{{ $wish->title }}"</span> 
                    has been successfully submitted to Santa's Workshop! 🎁
                </p>
                <p class="text-base md:text-lg text-gray-600 mb-8">
                    I've received your wish and I'm already checking my list! 
                    My elves are working hard to make your wish come true! 🧑‍🎄✨
                </p>
            </div>

            <!-- Tracking Number Card -->
            <div class="bg-gradient-to-r from-red-50 to-green-50 border-4 border-red-300 rounded-2xl p-8 mb-8 text-center shadow-lg">
                <p class="text-sm md:text-base text-gray-600 mb-3 font-semibold">Your Unique Tracking Number:</p>
                <div class="bg-white rounded-xl p-6 border-2 border-dashed border-red-400 inline-block">
                    <p class="text-4xl md:text-5xl font-bold text-red-600 tracking-wider font-mono">
                        {{ number_format($trackingNumber, 0, '.', ' ') }}
                    </p>
                </div>
                <p class="text-xs md:text-sm text-gray-500 mt-4">
                    📝 Save this number to track your wish anytime!
                </p>
            </div>

            <!-- Wish Summary -->
            <div class="bg-blue-50 p-6 rounded-xl border-2 border-blue-200 mb-6">
                <h3 class="text-xl font-semibold text-blue-800 mb-4 flex items-center">
                    <span class="mr-2">📋</span> Your Wish Summary
                </h3>
                <div class="space-y-3 text-gray-700">
                    <p><span class="font-semibold">Wish Title:</span> {{ $wish->title }}</p>
                    <p><span class="font-semibold">Description:</span> {{ $wish->description }}</p>
                    <p><span class="font-semibold">Priority:</span> 
                        <span class="px-2 py-1 rounded text-sm font-semibold
                            @if($wish->priority === 'high') bg-red-200 text-red-800
                            @elseif($wish->priority === 'medium') bg-yellow-200 text-yellow-800
                            @else bg-green-200 text-green-800
                            @endif">
                            {{ ucfirst($wish->priority) }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Fun Message from Santa -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-r-xl mb-6">
                <div class="flex items-start">
                    <span class="text-3xl mr-4">🎄</span>
                    <div>
                        <p class="text-lg font-semibold text-yellow-800 mb-2">A Special Message from Santa:</p>
                        <p class="text-gray-700 leading-relaxed">
                            "{{ $wish->name }}, I'm so excited to receive your wish! 
                            I've added it to my special list and I'll be reviewing it with my elves very soon. 
                            Remember, the best wishes come from the heart, and yours definitely does! 
                            Keep being kind and wonderful! 🎅✨"
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center space-y-4">
                <a href="/" class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-xl transition-colors duration-200 shadow-lg">
                    🏠 Back to Home
                </a>
                <p class="text-sm text-gray-500">
                    You can track your wish anytime using your tracking number: 
                    <span class="font-mono font-semibold">{{ number_format($trackingNumber, 0, '.', ' ') }}</span>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center p-6 bg-gray-50 text-gray-500 text-sm border-t-2 border-gray-200">
            <p class="mb-2">
                🎅 Made with ❤️ by Santa's Workshop Team 🎄
            </p>
            <p class="text-xs">
                We'll be in touch soon! Keep checking your tracking number for updates! 📧
            </p>
        </div>
    </div>
</body>
</html>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apotek AI Assistant</title>

    {{-- TAILWIND CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- CUSTOM STYLES for Light Liquid Glass Background --}}
    <style>
        body {
            /* Latar belakang Liquid/Gradien yang terang (pastel) */
            background-color: #f0f4f8;
            /* Warna dasar abu-abu sangat terang */
            background-image:
                radial-gradient(circle at 10% 20%, rgba(200, 240, 255, 0.6) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(255, 220, 240, 0.6) 0%, transparent 40%);
            background-blend-mode: multiply;
            background-repeat: no-repeat;
            background-size: 50% 50%;
        }
    </style>
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scale-up {
            from {
                transform: scale(0.8);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes bounce-slow {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-6px);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.8s ease-out forwards;
        }

        .animate-scale-up {
            animation: scale-up 0.8s ease-out 0.2s forwards;
        }

        .animate-fade-delayed {
            animation: fade-in 1s ease-out 0.4s forwards;
        }

        .animate-bounce-slow {
            animation: bounce-slow 2s infinite;
        }
    </style>

</head>

{{-- Ubah warna teks body default menjadi gelap --}}

<body class="text-gray-900 min-h-screen">

    {{-- SPLASH SCREEN FULLSCREEN --}}
    <div id="splash"
        class="fixed inset-0 z-[9999] flex items-center justify-center
           bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500
           text-white transition-opacity duration-700">

        <div class="flex flex-col items-center space-y-4 animate-fade-in">

            {{-- Logo --}}
            <div class="text-6xl animate-bounce-slow">💊</div>

            {{-- Title --}}
            <h1 class="text-3xl font-bold tracking-wide animate-scale-up">
                Apotek AI Assistant
            </h1>

            {{-- Subtitle --}}
            <p class="text-white/80 text-sm animate-fade-delayed">
                Loading your intelligent assistant...
            </p>
        </div>
    </div>
    <nav class="backdrop-blur-xl bg-white/70 shadow-lg sticky top-0 z-50 border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 py-3 flex justify-between items-center">

            {{-- BRAND --}}
            <a class="text-gray-900 text-xl font-bold tracking-wide flex items-center gap-2" href="#">
                💊 <span>Apotek AI Assistant</span>
            </a>

            {{-- HAMBURGER BUTTON (Mobile) --}}
            <button id="nav-toggle" class="md:hidden text-gray-800 text-2xl focus:outline-none">
                ☰
            </button>

            {{-- MENU --}}
            <div id="nav-menu"
                class="hidden md:flex flex-col md:flex-row items-start md:items-center gap-3 md:gap-4 mt-3 md:mt-0">

                {{-- UPLOAD DATA --}}
                @auth
                    <a href="{{ route('rag.upload.page') }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium bg-green-500/50 text-gray-700 hover:bg-white transition shadow-sm border border-gray-100 w-full md:w-auto">
                        Upload Data
                    </a>

                    <a href="{{ route('documents.index') }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium bg-yellow-500/50 text-gray-700 hover:bg-white transition shadow-sm border border-gray-100 w-full md:w-auto">
                        Dataset
                    </a>
                @endauth

                {{-- CHAT AI --}}
                <a href="{{ route('rag.chat.page') }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium bg-blue-600/90 text-white hover:bg-blue-700 transition shadow-lg w-full md:w-auto">
                    Chat AI
                </a>

                {{-- LOGIN --}}
                @guest
                    <a href="/login"
                        class="px-4 py-2 rounded-lg text-sm font-medium bg-green-600/90 text-white hover:bg-green-700 transition shadow-lg w-full md:w-auto">
                        Login
                    </a>
                @endguest

                {{-- LOGOUT --}}
                @auth
                    <form action="{{ route('logout') }}" method="POST" class="w-full md:w-auto">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 rounded-lg text-sm font-medium bg-red-600/90 text-white hover:bg-red-700 transition shadow-lg w-full md:w-auto">
                            Logout
                        </button>
                    </form>
                @endauth

            </div>

        </div>
    </nav>



    {{-- CONTENT --}}
    <div class="max-w-4xl mx-auto p-4">
        @yield('content')
    </div>

    <script>
        const toggleBtn = document.getElementById("nav-toggle");
        const menu = document.getElementById("nav-menu");

        toggleBtn.addEventListener("click", () => {
            menu.classList.toggle("hidden");
        }); <
    </script>

    <script>
        // Splash screen hilang setelah 1.5 detik
        setTimeout(() => {
            const splash = document.getElementById('splash');
            splash.classList.add('opacity-0');

            // Hapus dari DOM setelah animasi selesai
            setTimeout(() => splash.style.display = 'none', 700);
        }, 1500);
    </script>
</body>

</html>

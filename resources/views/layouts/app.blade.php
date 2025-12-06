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
</head>

{{-- Ubah warna teks body default menjadi gelap --}}

<body class="text-gray-900 min-h-screen">

    {{-- NAVBAR (Light Glassmorphism) --}}
    <nav class="backdrop-blur-xl bg-white/70 shadow-lg sticky top-0 z-50 border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 py-3 flex justify-between items-center">

            {{-- BRAND --}}
            <a class="text-gray-900 text-xl font-bold tracking-wide flex items-center gap-2" href="#">
                💊 <span>Apotek AI Assistant</span>
            </a>

            {{-- MENU --}}
            <div class="flex items-center space-x-3">

                {{-- UPLOAD DATA (hanya muncul jika sudah login) --}}
                @auth
                    <a href="{{ route('rag.upload.page') }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium bg-green-500/50 text-gray-700 hover:bg-white transition shadow-sm border border-gray-100">
                        Upload Data
                    </a>


                    <a href="{{ route('documents.index') }}"
                        class="px-4 py-2 rounded-lg text-sm font-medium bg-yellow-500/50 text-gray-700 hover:bg-white transition shadow-sm border border-gray-100">
                        Dataset
                    </a>
                @endauth

                {{-- CHAT AI (selalu muncul) --}}
                <a href="{{ route('rag.chat.page') }}"
                    class="px-4 py-2 rounded-lg text-sm font-medium bg-blue-600/90 text-white hover:bg-blue-700 transition shadow-lg">
                    Chat AI
                </a>

                {{-- LOGIN (hanya jika belum login) --}}
                @guest
                    <a href="/login"
                        class="px-4 py-2 rounded-lg text-sm font-medium bg-green-600/90 text-white hover:bg-green-700 transition shadow-lg">
                        Login
                    </a>
                @endguest

                {{-- LOGOUT (hanya jika sudah login) --}}
                @auth
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 rounded-lg text-sm font-medium bg-red-600/90 text-white hover:bg-red-700 transition shadow-lg">
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

</body>

</html>

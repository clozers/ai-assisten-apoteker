<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Apotek AI Assistant</title>

    {{-- TailwindCSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background-color: #eef3f8;
            background-image:
                radial-gradient(circle at 20% 30%, rgba(180, 220, 255, 0.5) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(255, 200, 230, 0.5) 0%, transparent 50%);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center text-gray-900">

    <div class="w-full max-w-md bg-white/60 backdrop-blur-lg shadow-xl rounded-2xl p-8 border border-white/40 space-y-6">

        {{-- TITLE --}}
        <h2 class="text-2xl font-bold text-center">Login</h2>
        <p class="text-center text-gray-600 text-sm">Masuk untuk menggunakan Apotek AI Assistant</p>

        {{-- ERROR MESSAGE --}}
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- FORM LOGIN --}}
        <form method="POST" action="/login" class="space-y-5">
            @csrf

            <div>
                <label class="block font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <div>
                <label class="block font-medium mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-400 focus:outline-none">
            </div>

            <button type="submit"
                class="w-full py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition shadow-md">
                Login
            </button>
        </form>

        {{-- OPTIONAL FOOTER --}}
        <p class="text-center text-gray-600 text-sm">
            Belum punya akses? Hubungi admin.
        </p>
    </div>

</body>

</html>

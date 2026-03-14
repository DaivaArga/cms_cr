<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name', 'Laravel') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>

<body class="min-h-screen bg-[#e8e8e8] flex items-center justify-center p-6">
    <div class="w-full max-w-sm bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <h1 class="text-xl font-semibold text-gray-900 mb-6">Login</h1>
        @if ($errors->any())
            <div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">
                {{ $errors->first() }}
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    autocomplete="email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 focus:ring-2 focus:ring-[#c8d44e] focus:border-[#c8d44e]">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password" required autocomplete="current-password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-gray-900 focus:ring-2 focus:ring-[#c8d44e] focus:border-[#c8d44e]">
            </div>
            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember"
                    class="rounded border-gray-300 text-[#c8d44e] focus:ring-[#c8d44e]">
                <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
            </div>
            <button type="submit"
                class="w-full py-2.5 px-4 bg-[#1a1a1a] text-white rounded-lg font-medium hover:bg-[#2a2a2a] transition-colors">
                Masuk
            </button>
        </form>
        <p class="mt-4 text-center text-sm text-gray-500">
            Setelah login Anda akan diarahkan ke dashboard admin.
        </p>
        @if (config('app.debug'))
            <p class="mt-2 text-center text-xs text-gray-400">
                Dummy: admin@admin.com / password
            </p>
            <p class="mt-2 text-center">
                <a href="/login-as-admin" class="text-sm text-[#c8d44e] font-semibold hover:underline">Login sebagai Admin
                    (dev)</a>
            </p>
        @endif
    </div>
</body>

</html>
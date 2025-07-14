<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title', 'Dashboard')</title>
    @vite('resources/css/app.css') {{-- pastikan Tailwind terhubung --}}
</head>

<body class="bg-gray-100 min-h-screen font-sans">
    <header class="bg-blue-800 text-white py-4 shadow">
        <div class="container mx-auto flex justify-between items-center px-4">
            <h1 class="text-xl font-bold">Admin Panel - Sepatuin</h1>
            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('admin.dashboard') }}" class="underline hover:text-gray-200 transition">Dashboard</a>
                <a href="{{ route('home') }}" class="underline hover:text-gray-200 transition">← Halaman Utama</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hover:text-gray-200 transition">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 py-6">
        @yield('content')
    </main>
    @stack('scripts')
</body>

</html>


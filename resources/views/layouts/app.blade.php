<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('meta_title', config('app.name', 'Sepatuin'))</title>
    @yield('meta')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 text-gray-800" style="font-family: 'Poppins', 'Figtree', sans-serif;">
    {{-- ✅ Navbar bawaan (menggunakan komponen navigation.blade.php) --}}
    @include('layouts.navigation')

    {{-- Page Heading (opsional, dipakai kalau ada $header) --}}
    @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    {{-- Page Content --}}
    <main class="min-h-[70vh]">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gradient-to-r from-indigo-600 via-blue-500 to-indigo-400 mt-12 shadow-inner">
        <div class="max-w-7xl mx-auto px-4 py-8 flex flex-col md:flex-row items-center justify-between text-white">
            <div class="mb-4 md:mb-0 text-center md:text-left">
                <span class="font-bold text-lg">Sepatuin</span> &copy; {{ date('Y') }}<br>
                <span class="text-xs">Semua hak dilindungi.</span>
            </div>
            <div class="flex space-x-4 justify-center md:justify-end">
                <a href="mailto:support@sepatuin.com" class="hover:text-yellow-300 transition" title="Email"><svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12H8m8 0a4 4 0 11-8 0 4 4 0 018 0zm0 0v4m0-4V8" /></svg></a>
                <a href="https://instagram.com/sepatuin.id" target="_blank" class="hover:text-yellow-300 transition" title="Instagram"><svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><rect width="20" height="20" x="2" y="2" rx="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><circle cx="17.5" cy="6.5" r="1.5"/></svg></a>
            </div>
        </div>
    </footer>
    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: @json(session('success')),
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        </script>
    @endif
</body>

</html>

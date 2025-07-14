@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                Selamat datang, {{ Auth::user()->name }}!<br>
                <hr class="my-4">
                <strong>Profil Anda:</strong><br>
                Email: {{ Auth::user()->email }}<br>
                <!-- Tambahkan info profil lain jika ada -->

                <hr class="my-4">
                <strong>Aktivitas Terakhir:</strong><br>
                Login terakhir: {{ Auth::user()->last_login ?? 'Baru pertama login' }}<br>
                <!-- Tambahkan aktivitas lain -->

                <hr class="my-4">
                <strong>Akses Cepat:</strong><br>
                <a href="/riwayat" class="text-blue-500 underline">Lihat Riwayat</a> |
                <a href="/profil" class="text-blue-500 underline">Edit Profil</a>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Cropper.js CSS & JS via CDN -->
<link  href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

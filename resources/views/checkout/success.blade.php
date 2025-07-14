@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-12 text-center">
        <div class="bg-white p-8 rounded-lg shadow-md max-w-xl mx-auto">
            <h1 class="text-3xl font-bold text-green-600 mb-4">✅ Checkout Berhasil!</h1>

            <p class="text-gray-700 mb-6">
                Terima kasih telah berbelanja di <span class="font-semibold">Sepatuin</span>.<br>
                Pesanan Anda sedang diproses dan akan segera dikirim.
            </p>

            <a href="{{ route('orders.index') }}"
                class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded mb-2">
                📦 Lihat Riwayat Pesanan
            </a>
            <br>
            <a href="{{ route('home') }}" class="text-sm text-blue-600 hover:underline">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>
@endsection

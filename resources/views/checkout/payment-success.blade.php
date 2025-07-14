@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <div class="max-w-2xl mx-auto">
            {{-- Icon Sukses --}}
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold text-green-600 mb-2">Pembayaran Berhasil!</h1>
                <p class="text-gray-600">Terima kasih telah berbelanja di Sepatuin</p>
            </div>

            {{-- Detail Pembayaran --}}
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <h2 class="text-2xl font-bold mb-6 text-indigo-700">Detail Pembayaran</h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="font-medium text-gray-600">Order ID</span>
                        <span class="font-semibold">#{{ $firstOrder->id }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="font-medium text-gray-600">Metode Pembayaran</span>
                        <span class="font-semibold capitalize">{{ $paymentMethod }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="font-medium text-gray-600">Status</span>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">Lunas</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 border-b border-gray-200">
                        <span class="font-medium text-gray-600">Total Pembayaran</span>
                        <span class="font-bold text-xl text-indigo-600">Rp {{ number_format($firstOrder->total_price, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-3">
                        <span class="font-medium text-gray-600">Tanggal Pembayaran</span>
                        <span class="font-semibold">{{ $firstOrder->updated_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>

            {{-- Detail Produk --}}
            <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
                <h2 class="text-2xl font-bold mb-6 text-indigo-700">Detail Produk</h2>
                
                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-16 h-16 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg">{{ $firstOrder->product->name }}</h3>
                        <p class="text-gray-600">Qty: {{ $firstOrder->quantity }}</p>
                        <p class="text-indigo-600 font-semibold">Rp {{ number_format($firstOrder->product->price, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex flex-col md:flex-row gap-4">
                <a href="{{ route('orders.index') }}" 
                   class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-full text-center transition duration-200">
                    Lihat Pesanan Saya
                </a>
                
                <a href="{{ route('home') }}" 
                   class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 px-6 rounded-full text-center transition duration-200">
                    Lanjut Belanja
                </a>
            </div>

            {{-- Info Tambahan --}}
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">
                    Email konfirmasi telah dikirim ke <strong>{{ auth()->user()->email }}</strong>
                </p>
                <p class="text-sm text-gray-500 mt-2">
                    Tim kami akan segera memproses pesanan Anda
                </p>
            </div>
        </div>
    </div>
@endsection 
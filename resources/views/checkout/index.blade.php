@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <h1 class="text-3xl font-extrabold text-center text-indigo-700 mb-8 drop-shadow">Checkout</h1>

        {{-- Alert sukses --}}
        @if (session('success'))
            <div class="mb-8 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow text-center font-semibold">
                {{ session('success') }}
            </div>
        @endif

        {{-- Ringkasan belanja --}}
        <div class="bg-white rounded-2xl shadow-xl p-8 max-w-2xl mx-auto">
            <h2 class="text-2xl font-bold mb-6 text-indigo-700">Ringkasan Belanja</h2>

            <ul class="divide-y divide-gray-200 mb-6">
                @foreach ($cartItems as $item)
                    <li class="py-3 flex justify-between items-center">
                        <span class="font-medium">{{ $item->product->name }} <span class="text-xs text-gray-500">({{ $item->size }})</span> <span class="text-xs text-gray-500">x{{ $item->quantity }}</span></span>
                        <span class="font-semibold">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>

            <div class="flex justify-between items-center font-bold text-xl border-t pt-6">
                <span>Total</span>
                <span class="text-indigo-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-8 flex flex-col md:flex-row justify-between gap-4">
                <a href="{{ route('cart.index') }}"
                    class="bg-gray-200 text-gray-700 px-6 py-3 rounded-full font-semibold hover:bg-gray-300 transition text-sm shadow">
                    ← Kembali ke Keranjang
                </a>

                <form action="{{ route('checkout.pay') }}" method="GET">
                    <button type="submit" class="bg-yellow-300 hover:bg-yellow-400 text-indigo-900 font-bold px-8 py-3 rounded-full shadow-lg transition-all duration-200">
                        Bayar Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

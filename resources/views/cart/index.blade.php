@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-extrabold mb-6 text-indigo-700 text-center drop-shadow">Keranjang Saya</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded-lg mb-6 shadow text-center font-semibold">{{ session('success') }}</div>
        @endif

        @if ($cartItems->isEmpty())
            <p class="text-gray-500 text-center text-lg">Keranjang Anda kosong.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full bg-white rounded-2xl shadow-lg overflow-hidden">
                    <thead class="bg-indigo-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-indigo-700">Produk</th>
                            <th class="px-6 py-3 font-semibold text-indigo-700">Harga</th>
                            <th class="px-6 py-3 font-semibold text-indigo-700">Qty</th>
                            <th class="px-6 py-3 font-semibold text-indigo-700">Subtotal</th>
                            <th class="px-6 py-3 font-semibold text-indigo-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $item)
                            <tr class="border-b hover:bg-indigo-50 transition">
                                <td class="px-6 py-3">{{ $item->product->name }} <span class="text-xs text-gray-500">(Size {{ $item->size }})</span></td>
                                <td class="px-6 py-3">Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-3">{{ $item->quantity }}</td>
                                <td class="px-6 py-3">
                                    Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3">
                                    <form action="{{ route('cart.remove', $item) }}" method="POST"
                                        onsubmit="return confirm('Hapus item ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-semibold hover:bg-red-200 transition">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="font-bold text-xl text-indigo-700">
                    Total: Rp {{ number_format($cartItems->sum(fn($i) => $i->product->price * $i->quantity), 0, ',', '.') }}
                </p>
                <a href="{{ route('checkout.index') }}" class="bg-yellow-300 hover:bg-yellow-400 text-indigo-900 font-bold px-8 py-3 rounded-full shadow-lg transition-all duration-200">
                    Lanjut ke Checkout
                </a>
            </div>
        @endif
    </div>
@endsection

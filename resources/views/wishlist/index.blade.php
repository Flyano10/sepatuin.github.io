@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <h1 class="text-3xl font-extrabold text-indigo-700 mb-8 drop-shadow">Wishlist Saya</h1>

        @if (session('success'))
            <div class="mb-8 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow text-center font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if ($wishlists->isEmpty())
            <div class="bg-white rounded-xl shadow p-8 text-center text-gray-500">
                Belum ada produk di wishlist.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($wishlists as $item)
                    <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
                        @if ($item->product && $item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-32 h-32 object-cover rounded mb-4">
                        @else
                            <div class="w-32 h-32 bg-gray-200 flex items-center justify-center text-gray-500 rounded mb-4">Tidak ada gambar</div>
                        @endif
                        <h2 class="text-lg font-bold text-indigo-700 mb-2 text-center">{{ $item->product->name ?? '-' }}</h2>
                        <p class="text-gray-600 mb-2 text-center">Rp {{ number_format($item->product->price ?? 0, 0, ',', '.') }}</p>
                        <div class="flex gap-2 mt-2">
                            <a href="{{ route('products.show', $item->product) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-full text-sm font-semibold transition">Lihat</a>
                            <form action="{{ route('wishlist.destroy', $item->product) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-pink-100 hover:bg-pink-200 text-pink-700 px-4 py-2 rounded-full text-sm font-semibold transition">Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection 
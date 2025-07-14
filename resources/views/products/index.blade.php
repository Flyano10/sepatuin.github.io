@extends('layouts.app')

@section('content')
    {{-- Hero Section --}}
    <div class="bg-gradient-to-r from-indigo-600 via-blue-500 to-indigo-400 text-white py-16 mb-10 shadow-lg">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl font-extrabold mb-4 drop-shadow-lg">
                Selamat Datang di <span class="text-yellow-300">Sepatuin</span>!
            </h1>
            <p class="text-xl mb-6 font-light">Temukan sepatu favoritmu dengan harga terbaik</p>
            <a href="#products"
                class="mt-6 inline-block bg-yellow-300 text-indigo-900 font-bold px-8 py-3 rounded-full shadow-lg hover:bg-yellow-400 hover:scale-105 transition-all duration-200">
                Belanja Sekarang
            </a>
        </div>
    </div>

    <div id="products" class="container mx-auto px-4 pb-10">
        {{-- Filter Kategori --}}
        <form action="{{ route('home') }}" method="GET" class="mb-8 max-w-3xl mx-auto flex flex-col sm:flex-row gap-3 sm:gap-4 items-center justify-center">
            <input type="text" name="search" value="{{ request('search') }}"
                class="flex-1 px-5 py-3 rounded-full text-gray-700 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition w-full sm:w-auto" placeholder="Cari produk...">
            <select name="category" onchange="this.form.submit()" class="px-4 py-3 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition w-full sm:w-auto">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            <select name="sort" onchange="this.form.submit()" class="px-4 py-3 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition w-full sm:w-auto">
                <option value="">Urutkan</option>
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Termurah</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Termahal</option>
            </select>
            <button type="submit" class="bg-indigo-600 text-white px-8 py-3 font-semibold rounded-full shadow hover:bg-indigo-700 transition w-full sm:w-auto">Cari</button>
        </form>

        {{-- Alert Sukses --}}
        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Grid Produk --}}
        <h2 class="text-3xl font-bold mb-8 text-center text-gray-800">Produk Terbaru</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @forelse ($products as $product)
                <div class="bg-white rounded-2xl shadow-lg p-5 flex flex-col hover:shadow-2xl hover:scale-105 transition-all duration-200 border border-gray-100">
                    {{-- Gambar --}}
                    @php
                        $mainImage = $product->images->count() > 0 ? $product->images->first()->image : $product->image;
                    @endphp

                    @if ($mainImage)
                        <img src="{{ asset('storage/' . $mainImage) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover rounded-xl mb-4 shadow">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center rounded-xl mb-4 text-gray-500">
                            Tidak ada gambar
                        </div>
                    @endif

                    {{-- Info Produk --}}
                    <h2 class="text-lg font-bold text-gray-900">{{ $product->name }}</h2>
                    @php
                        $avgRating = $product->reviews()->avg('rating');
                        $reviewCount = $product->reviews()->count();
                    @endphp
                    @if($reviewCount > 0)
                        <div class="mb-1">
                            <span class="text-yellow-500 font-bold">★ {{ number_format($avgRating, 1) }}</span>
                            <span class="text-gray-500 text-xs">({{ $reviewCount }} review)</span>
                        </div>
                    @endif
                    <p class="text-xs text-gray-500 mb-1">Kategori: {{ $product->category->name ?? '-' }}</p>
                    <p class="text-gray-500 text-sm mt-1">
                        {{ \Illuminate\Support\Str::limit($product->description, 60) }}
                    </p>
                    <p class="text-indigo-600 font-bold text-lg mt-2">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    {{-- Badge stok --}}
                    @if ($product->stock > 0)
                        <span class="inline-block bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full mt-2">Stok Tersedia</span>
                    @else
                        <span class="inline-block bg-red-100 text-red-700 text-xs font-semibold px-3 py-1 rounded-full mt-2">Stok Habis</span>
                    @endif

                    {{-- Tombol Detail --}}
                    <a href="{{ route('products.show', $product) }}"
                        class="mt-auto text-center bg-indigo-600 hover:bg-indigo-700 text-white rounded-full px-6 py-2 text-sm font-semibold mt-4 shadow hover:scale-105 transition-all duration-200">
                        Lihat Detail
                    </a>
                </div>
            @empty
                <p class="text-gray-500 col-span-full text-center">Belum ada produk tersedia.</p>
            @endforelse
        </div>
    </div>
{{-- Pagination --}}
<div class="mt-8 flex justify-center">
    {{ $products->links() }}
</div>
@endsection

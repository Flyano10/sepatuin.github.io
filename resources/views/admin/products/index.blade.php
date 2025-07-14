@extends('layouts.admin')

@section('title', 'Daftar Produk')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Daftar Produk</h1>
        <a href="{{ route('admin.products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            + Tambah Produk
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter Kategori & Search --}}
    <form action="" method="GET" class="mb-6 flex gap-4 items-center">
        <div>
            <select name="category" onchange="this.form.submit()" class="px-4 py-2 rounded border border-gray-300">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="px-4 py-2 rounded border border-gray-300" />
        </div>
        <div>
            <select name="sort" onchange="this.form.submit()" class="px-4 py-2 rounded border border-gray-300">
                <option value="">Urutkan</option>
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Termurah</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Termahal</option>
                <option value="stock_asc" {{ request('sort') == 'stock_asc' ? 'selected' : '' }}>Stok Tersedikit</option>
                <option value="stock_desc" {{ request('sort') == 'stock_desc' ? 'selected' : '' }}>Stok Terbanyak</option>
            </select>
        </div>
        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Cari</button>
        </div>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @forelse ($products as $product)
            <div class="bg-white p-4 rounded shadow hover:shadow-md transition">
                @php
                    $mainImage = $product->images->count() > 0 ? $product->images->first()->image : $product->image;
                @endphp
                @if ($mainImage)
                    <img src="{{ asset('storage/' . $mainImage) }}" alt="{{ $product->name }}"
                        class="w-full h-40 object-cover rounded mb-3">
                @else
                    <div class="w-full h-40 bg-gray-100 flex items-center justify-center text-gray-400 rounded mb-3">
                        Tidak ada gambar
                    </div>
                @endif

                <h2 class="text-lg font-semibold">{{ $product->name }}</h2>
                <p class="text-xs text-gray-500 mb-1">Kategori: {{ $product->category->name ?? '-' }}</p>
                <p class="text-gray-600 text-sm mt-1">{{ \Illuminate\Support\Str::limit($product->description, 50) }}</p>
                <p class="text-indigo-600 font-bold mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>

                @if ($product->stock < 5)
                    <span class="inline-block bg-red-100 text-red-700 text-xs font-semibold px-3 py-1 rounded-full mt-2 mb-2">Stok Menipis ({{ $product->stock }})</span>
                @else
                    <span class="inline-block bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full mt-2 mb-2">Stok: {{ $product->stock }}</span>
                @endif

                <div class="mt-4 flex justify-between items-center">
                    <a href="{{ route('admin.products.edit', $product) }}" class="text-blue-500 hover:underline text-sm">
                        ✏️ Edit
                    </a>
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline text-sm">🗑️ Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-center col-span-full text-gray-500">Belum ada produk tersedia.</p>
        @endforelse
    </div>
{{-- Pagination --}}
<div class="mt-8 flex justify-center">
    {{ $products->links() }}
</div>
@endsection

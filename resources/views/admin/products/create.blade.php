@extends('layouts.admin')

@section('title', 'Tambah Produk')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-4">Tambah Produk</h1>

        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-600 p-4 rounded">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white p-6 rounded shadow-md space-y-4">
            @csrf

            {{-- Nama Produk --}}
            <div>
                <label for="name" class="block font-medium text-sm text-gray-700">Nama Produk</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="mt-1 block w-full border border-gray-300 p-2 rounded focus:ring focus:ring-blue-200" required>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="description" class="block font-medium text-sm text-gray-700">Deskripsi</label>
                <textarea name="description" id="description" rows="4"
                    class="mt-1 block w-full border border-gray-300 p-2 rounded focus:ring focus:ring-blue-200" required>{{ old('description') }}</textarea>
            </div>

            {{-- Harga --}}
            <div>
                <label for="price" class="block font-medium text-sm text-gray-700">Harga</label>
                <input type="number" name="price" id="price" value="{{ old('price') }}"
                    class="mt-1 block w-full border border-gray-300 p-2 rounded focus:ring focus:ring-blue-200" required>
            </div>

            {{-- Stok per Size --}}
            <div>
                <label class="block font-medium text-sm text-gray-700 mb-1">Stok per Size</label>
                <div class="grid grid-cols-3 gap-2">
                    @for ($size = 35; $size <= 45; $size++)
                        <div class="flex items-center gap-2">
                            <label class="w-10">{{ $size }}</label>
                            <input type="number" name="sizes[{{ $size }}]" min="0" value="{{ old('sizes.' . $size, 0) }}" class="w-20 border border-gray-300 p-1 rounded" />
                        </div>
                    @endfor
                </div>
                <span class="text-xs text-gray-500">Isi stok untuk setiap size (boleh 0 jika tidak tersedia).</span>
            </div>

            {{-- Kategori Produk --}}
            <div>
                <label for="category_id" class="block font-medium text-sm text-gray-700">Kategori</label>
                <select name="category_id" id="category_id" class="mt-1 block w-full border border-gray-300 p-2 rounded focus:ring focus:ring-blue-200" required>
                    <option value="" disabled selected>Pilih Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Galeri Gambar Produk --}}
            <div>
                <label for="images" class="block font-medium text-sm text-gray-700">Galeri Gambar (bisa lebih dari satu)</label>
                <input type="file" name="images[]" id="images" multiple
                    class="mt-1 block w-full border border-gray-300 p-2 rounded">
                <span class="text-xs text-gray-500">Bisa upload beberapa gambar sekaligus.</span>
            </div>

            {{-- Tombol Simpan --}}
            <div class="pt-4">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition duration-150">
                    ➕ Simpan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('description');
</script>
@endpush

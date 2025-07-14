@extends('layouts.app')

@section('meta_title', $product->name . ' | Sepatuin')
@section('meta')
    <meta name="description" content="{{ Str::limit(strip_tags($product->description), 150) }}">
    <meta property="og:title" content="{{ $product->name }} | Sepatuin">
    <meta property="og:description" content="{{ Str::limit(strip_tags($product->description), 150) }}">
    <meta property="og:image" content="{{ asset('storage/' . ($product->images->first()->image ?? $product->image)) }}">
    <meta property="og:type" content="product">
    <meta property="og:url" content="{{ url()->current() }}">
@endsection

@section('content')
    <div class="container mx-auto px-4 py-10">
        <div class="max-w-3xl mx-auto bg-white shadow-2xl rounded-2xl p-8">
            {{-- ✅ Alert sukses --}}
            @if (session('success'))
                <div class="mb-8 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow text-center font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ✅ Galeri Gambar Produk Interaktif --}}
            @php
                $gallery = $product->images;
                $mainImage = $gallery->count() > 0 ? $gallery->first()->image : $product->image;
            @endphp
            <div class="flex flex-col items-center mb-4">
                <img id="mainProductImage" src="{{ asset('storage/' . $mainImage) }}" alt="{{ $product->name }}"
                    class="w-full h-72 object-cover rounded-xl shadow mb-2 transition-all duration-200 cursor-zoom-in" style="max-width: 500px;">
                @if ($gallery->count() > 1)
                    <div class="flex gap-2 mt-2" id="thumbGallery">
                        @foreach ($gallery as $i => $img)
                            <img src="{{ asset('storage/' . $img->image) }}" alt="{{ $product->name }}"
                                class="w-20 h-20 object-cover rounded-lg border border-gray-200 shadow-sm cursor-pointer thumb-img @if($i === 0) border-4 border-indigo-500 active-thumb @endif"
                                onclick="changeMainImage(this)">
                        @endforeach
                    </div>
                @endif
            </div>
            <script>
                function changeMainImage(el) {
                    const mainImg = document.getElementById('mainProductImage');
                    mainImg.src = el.src;
                    // Highlight active thumb
                    document.querySelectorAll('.thumb-img').forEach(img => img.classList.remove('border-4', 'border-indigo-500', 'active-thumb'));
                    el.classList.add('border-4', 'border-indigo-500', 'active-thumb');
                }
                // Optional: Zoom effect on hover
                const mainImg = document.getElementById('mainProductImage');
                if(mainImg) {
                    mainImg.addEventListener('mouseenter', function() {
                        mainImg.style.transform = 'scale(1.15)';
                    });
                    mainImg.addEventListener('mouseleave', function() {
                        mainImg.style.transform = 'scale(1)';
                    });
                }
            </script>

            {{-- ✅ Info Produk --}}
            <h1 class="text-4xl font-extrabold mb-3 text-indigo-700 drop-shadow">{{ $product->name }}</h1>
            @if($product->category)
                <span class="inline-block bg-indigo-100 text-indigo-700 text-xs font-semibold px-4 py-1 rounded-full mb-2">{{ $product->category->name }}</span>
            @endif
            @php
                $avgRating = $product->reviews()->avg('rating');
                $reviewCount = $product->reviews()->count();
            @endphp
            @if($reviewCount > 0)
                <div class="mb-4 flex items-center gap-2">
                    <span class="text-yellow-400 text-2xl font-bold">★</span>
                    <span class="text-2xl font-bold text-gray-800">{{ number_format($avgRating, 1) }}</span>
                    <span class="text-gray-500 text-base">({{ $reviewCount }} review)</span>
                </div>
            @endif
            <p class="text-gray-600 mb-6 text-lg">{!! $product->description !!}</p>
            <p class="text-indigo-600 text-3xl font-bold mb-4">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </p>
            <div class="mb-4 text-sm text-gray-500 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-500 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h2a4 4 0 014 4v2"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19v2m0 0H9m3 0h3"></path></svg>
                Estimasi tiba <span class="font-semibold text-gray-700">2-3 hari kerja</span>
            </div>

            {{-- ✅ Status Stok --}}
            @if ($product->stock > 0)
                <span class="inline-block bg-green-100 text-green-700 text-xs font-semibold px-4 py-1 rounded-full mb-8">Stok Tersedia</span>
            @else
                <span class="inline-block bg-red-100 text-red-700 text-xs font-semibold px-4 py-1 rounded-full mb-8">Stok Habis</span>
            @endif

            {{-- Tombol Wishlist & Keranjang --}}
            <div class="flex flex-wrap gap-3 items-center mb-6">
                {{-- Tombol Wishlist --}}
                @php
                    $inWishlist = auth()->user()->wishlists()->where('product_id', $product->id)->exists();
                @endphp
                @if ($inWishlist)
                    <form action="{{ route('wishlist.destroy', $product) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-pink-100 hover:bg-pink-200 text-pink-700 font-bold px-6 py-2 rounded-full shadow transition-all duration-200 text-sm">
                            ♥ Hapus dari Wishlist
                        </button>
                    </form>
                @else
                    <form action="{{ route('wishlist.store', $product) }}" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white font-bold px-6 py-2 rounded-full shadow transition-all duration-200 text-sm">
                            + Wishlist
                        </button>
                    </form>
                @endif

                {{-- Pilihan Size --}}
                @php
                    $sizes = $product->sizes()->orderBy('size')->get();
                @endphp
                @if($sizes->count() > 0)
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="flex flex-col gap-3">
                        @csrf
                        <div class="mb-2">
                            <label class="font-semibold mb-1 block">Pilih Size:</label>
                            <div class="flex flex-wrap gap-4"> {{-- gap diperlebar --}}
                                @foreach($sizes as $size)
                                    @php
                                        $isAvailable = $size->stock > 0;
                                    @endphp
                                    <label class="flex items-center gap-2 group">
                                        <input type="radio" name="size" value="{{ $size->size }}" @if(!$isAvailable) disabled @endif required class="hidden peer">
                                        <span class="px-3 py-2 rounded border text-sm select-none transition
                                            @if($isAvailable)
                                                bg-gray-100 border-gray-300 text-gray-800 cursor-pointer
                                                peer-checked:bg-blue-100 peer-checked:border-blue-500 peer-checked:text-blue-700
                                                group-hover:border-blue-400 group-hover:bg-blue-50
                                            @else
                                                bg-gray-200 border-gray-300 text-gray-400 cursor-not-allowed opacity-60
                                            @endif
                                        " style="min-width:36px; text-align:center;">
                                            {{ $size->size }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="number" name="quantity" value="1" min="1" max="10" required class="w-20 px-3 py-2 border border-gray-300 rounded-full text-center focus:outline-none">
                            <button type="submit" class="bg-yellow-300 hover:bg-yellow-400 text-indigo-900 font-bold px-6 py-2 rounded-full shadow transition-all duration-200 text-sm">
                                + Keranjang
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-red-500 font-semibold mb-4">Semua size habis, produk tidak bisa dibeli.</div>
                @endif

                {{-- Tombol Share ke WhatsApp --}}
                <a href="https://wa.me/?text={{ urlencode(url()->current()) }}" target="_blank" class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-full text-sm font-semibold transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.52 3.48A12 12 0 003.48 20.52l-1.32 4.84a1 1 0 001.22 1.22l4.84-1.32A12 12 0 0020.52 3.48zm-8.52 16.5a10.5 10.5 0 01-5.94-1.8l-.42-.28-2.87.78.78-2.87-.28-.42A10.5 10.5 0 1112 19.98zm5.07-7.75c-.07-.15-.27-.24-.57-.42s-1.68-.83-1.94-.92c-.26-.09-.45-.13-.64.13-.19.26-.74.92-.91 1.11-.17.19-.34.21-.63.07-.29-.14-1.23-.45-2.34-1.44-.86-.77-1.44-1.72-1.61-2.01-.17-.29-.02-.45.13-.59.13-.13.29-.34.43-.51.14-.17.19-.29.29-.48.1-.19.05-.36-.02-.51-.07-.15-.64-1.54-.88-2.11-.23-.56-.47-.48-.64-.49-.16-.01-.36-.01-.56-.01s-.51.07-.78.36c-.27.29-1.04 1.02-1.04 2.5 0 1.48 1.07 2.91 1.22 3.11.15.2 2.1 3.21 5.09 4.38.71.29 1.26.46 1.69.59.71.23 1.36.2 1.87.12.57-.09 1.68-.69 1.92-1.36.24-.67.24-1.24.17-1.36z"/></svg>
                    Bagikan Produk
                </a>
            </div>

            {{-- Form Review & Daftar Review --}}
            @auth
                @php
                    $userReview = $product->reviews()->where('user_id', auth()->id())->first();
                    $canReview = auth()->user()->hasBoughtProduct($product->id);
                @endphp
                @if($canReview)
                    <div class="bg-gray-50 rounded-xl p-6 mb-6 mt-8">
                        <h2 class="text-lg font-bold mb-2 text-indigo-700">Review Produk</h2>
                        <form action="{{ route('review.store', $product) }}" method="POST" class="flex flex-col gap-3">
                            @csrf
                            <div class="flex items-center gap-2">
                                <label class="font-semibold">Rating:</label>
                                <select name="rating" required class="rounded border-gray-300 px-2 py-1">
                                    <option value="">Pilih rating</option>
                                    @for ($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" @if(optional($userReview)->rating == $i) selected @endif>{{ $i }} ★</option>
                                    @endfor
                                </select>
                            </div>
                            <textarea name="review" rows="2" class="rounded border-gray-300 px-3 py-2" placeholder="Tulis review...">{{ old('review', optional($userReview)->review) }}</textarea>
                            <div class="flex gap-2">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-2 rounded-full shadow transition-all duration-200 text-sm">
                                    {{ $userReview ? 'Update Review' : 'Kirim Review' }}
                                </button>
                                @if($userReview)
                                    <form action="{{ route('review.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus review ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 font-bold px-6 py-2 rounded-full shadow transition-all duration-200 text-sm">Hapus</button>
                                    </form>
                                @endif
                            </div>
                        </form>
                    </div>
                @else
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-xl p-6 mb-6 mt-8 text-center">
                        Hanya pembeli produk ini yang bisa memberikan review.
                    </div>
                @endif
            @endauth

            {{-- Daftar Review Produk --}}
            <div class="bg-white rounded-xl shadow p-6 mt-4">
                <h2 class="text-lg font-bold mb-4 text-indigo-700">Ulasan Pembeli</h2>
                @forelse($product->reviews()->latest()->get() as $review)
                    <div class="mb-6 border-b pb-4 flex gap-4 items-start">
                        {{-- Avatar/inisial user --}}
                        <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xl shadow">
                            {{ strtoupper(substr($review->user->name, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-yellow-400 text-xl font-bold">★</span>
                                <span class="text-lg font-bold text-gray-800">{{ $review->rating }}</span>
                                <span class="text-gray-700 font-semibold">{{ $review->user->name }}</span>
                                <span class="text-gray-400 text-xs">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="text-gray-700">{{ $review->review }}</div>
                        </div>
                    </div>
                @empty
                    <div class="text-gray-400">Belum ada review untuk produk ini.</div>
                @endforelse
            </div>

            {{-- ✅ Tombol kembali --}}
            <a href="{{ route('home') }}"
                class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-full text-sm font-semibold mt-4 shadow transition">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>
@endsection

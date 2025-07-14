<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    // Halaman utama (untuk pelanggan umum)
    public function index(Request $request)
    {
        $query = Product::query();
        $categories = Category::all();

        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        // Sorting
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        $products = $query->latest()->paginate(9);
        return view('products.index', compact('products', 'categories'));
    }

    // Tampilkan detail produk
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    // Halaman admin - daftar produk
    public function adminIndex(Request $request)
    {
        $query = Product::query();
        $categories = Category::all();
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        // Sorting
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'stock_asc':
                $query->orderBy('stock', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('stock', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        $products = $query->paginate(12);
        return view('admin.products.index', compact('products', 'categories'));
    }

    // Tampilkan form tambah produk (admin)
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    // Simpan produk baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $product = Product::create($validated);

        // Simpan stok per size
        if ($request->has('sizes')) {
            foreach ($request->sizes as $size => $stock) {
                if ((int)$stock > 0) {
                    $product->sizes()->create([
                        'size' => $size,
                        'stock' => $stock,
                    ]);
                }
            }
        }

        // Simpan galeri gambar jika ada
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $image) {
                // Kompres gambar sebelum simpan
                $img = Image::make($image)->encode('jpg', 75); // 75% quality
                $filename = 'products/' . uniqid() . '.jpg';
                Storage::disk('public')->put($filename, $img);
                $product->images()->create([
                    'image' => $filename,
                    'order' => $i,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    // Tampilkan form edit produk
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // Proses update produk
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $product->update($validated);

        // Update stok per size
        if ($request->has('sizes')) {
            // Hapus semua size lama, lalu insert ulang (simple way)
            $product->sizes()->delete();
            foreach ($request->sizes as $size => $stock) {
                if ((int)$stock > 0) {
                    $product->sizes()->create([
                        'size' => $size,
                        'stock' => $stock,
                    ]);
                }
            }
        }

        // Upload gambar baru ke galeri
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $image) {
                // Kompres gambar sebelum simpan
                $img = Image::make($image)->encode('jpg', 75); // 75% quality
                $filename = 'products/' . uniqid() . '.jpg';
                Storage::disk('public')->put($filename, $img);
                $product->images()->create([
                    'image' => $filename,
                    'order' => $product->images()->count() + $i,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    // Hapus gambar galeri produk
    public function deleteImage(\App\Models\ProductImage $image)
    {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($image->image);
        $image->delete();
        return back()->with('success', 'Gambar berhasil dihapus!');
    }

    // Hapus produk
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}

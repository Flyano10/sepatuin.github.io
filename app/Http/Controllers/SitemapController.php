<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Models\Product;
use Illuminate\Support\Facades\URL;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = [];
        // Homepage
        $urls[] = url('/');
        // Produk
        foreach (Product::all() as $product) {
            $urls[] = route('products.show', $product);
        }
        // (Opsional) Tambahkan kategori, dsb jika ada

        return response()->view('sitemap.xml', compact('urls'), 200)
            ->header('Content-Type', 'application/xml');
    }
}

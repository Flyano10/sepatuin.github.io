<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;
use App\Models\Product;

class WishlistController extends Controller
{
    // List wishlist user
    public function index()
    {
        $wishlists = Wishlist::with('product')
            ->where('user_id', Auth::id())
            ->get();
        return view('wishlist.index', compact('wishlists'));
    }

    // Tambah ke wishlist
    public function store(Request $request, $productId)
    {
        $userId = Auth::id();
        $exists = Wishlist::where('user_id', $userId)->where('product_id', $productId)->exists();
        if (!$exists) {
            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
        }
        return back()->with('success', 'Produk ditambahkan ke wishlist!');
    }

    // Hapus dari wishlist
    public function destroy($productId)
    {
        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();
        return back()->with('success', 'Produk dihapus dari wishlist!');
    }
}

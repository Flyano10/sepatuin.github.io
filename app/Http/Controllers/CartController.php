<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Menampilkan semua item di keranjang user
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return view('cart.index', compact('cartItems'));
    }

    // Menambahkan produk ke keranjang
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'size' => 'required|integer',
        ]);

        // Validasi size tersedia dan stok cukup
        $sizeObj = $product->sizes()->where('size', $request->size)->first();
        if (!$sizeObj || $sizeObj->stock < $request->quantity) {
            return back()->with('error', 'Stok size yang dipilih tidak cukup atau tidak tersedia.');
        }

        // Cek apakah produk+size sudah ada di keranjang
        $existingCartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('size', $request->size)
            ->first();

        if ($existingCartItem) {
            // Tambahkan quantity, cek stok
            $newQty = $existingCartItem->quantity + $request->quantity;
            if ($newQty > $sizeObj->stock) {
                return back()->with('error', 'Stok size yang dipilih tidak cukup.');
            }
            $existingCartItem->quantity = $newQty;
            $existingCartItem->save();
        } else {
            // Buat baru
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'size' => $request->size,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    // Menghapus item dari keranjang
    public function remove(Cart $cart)
    {
        // Pastikan hanya user pemilik item yang bisa menghapus
        if ($cart->user_id !== Auth::id()) {
            abort(403, 'Tidak diizinkan menghapus item ini.');
        }

        $cart->delete();

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}

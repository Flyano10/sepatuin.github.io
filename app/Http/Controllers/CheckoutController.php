<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Order;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;
use App\Helpers\MidtransHelper;
use App\Models\OrderItem;
use App\Models\ProductSize;

class CheckoutController extends Controller
{
    /**
     * ✅ Menampilkan halaman checkout
     */
    public function index()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('checkout.index', compact('cartItems', 'total'));
    }

    /**
     * ✅ Memproses pesanan dari keranjang
     */
    public function process(Request $request)
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        foreach ($cartItems as $item) {
            $order = Order::create([
                'user_id'     => Auth::id(),
                'product_id'  => $item->product_id,
                'quantity'    => $item->quantity,
                'total_price' => $item->product->price * $item->quantity,
                'status'      => 'pending',
            ]);

            Mail::to(Auth::user()->email)->send(new OrderConfirmationMail($order, Auth::user(), $item->product));
        }

        // Hapus isi keranjang
        Cart::where('user_id', Auth::id())->delete();

        // Redirect ke halaman sukses checkout
        return redirect()->route('checkout.success')->with('success', 'Checkout berhasil! Pesanan Anda sedang diproses.');
    }

    /**
     * ✅ Menampilkan halaman pilihan metode pembayaran
     */
    public function pay(Request $request)
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('checkout.payment', compact('cartItems', 'total'));
    }

    /**
     * ✅ Memproses pembayaran simulasi (tanpa Midtrans)
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string'
        ]);

        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        // Validasi stok size cukup untuk semua item
        foreach ($cartItems as $item) {
            $sizeObj = ProductSize::where('product_id', $item->product_id)
                ->where('size', $item->size)
                ->first();
            if (!$sizeObj || $sizeObj->stock < $item->quantity) {
                return redirect()->route('cart.index')->with('error', 'Stok size ' . $item->size . ' untuk produk ' . $item->product->name . ' tidak cukup.');
            }
        }

        // Buat order untuk setiap item di cart
        $orders = [];
        foreach ($cartItems as $item) {
            $order = Order::create([
                'user_id'     => Auth::id(),
                'product_id'  => $item->product_id,
                'quantity'    => $item->quantity,
                'total_price' => $item->product->price * $item->quantity,
                'status'      => 'paid', // Langsung set ke paid
            ]);

            // Simpan ke order_items (dengan size)
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product_id,
                'size'       => $item->size,
                'quantity'   => $item->quantity,
                'price'      => $item->product->price,
            ]);

            // Kurangi stok product_sizes
            $sizeObj = ProductSize::where('product_id', $item->product_id)
                ->where('size', $item->size)
                ->first();
            if ($sizeObj) {
                $sizeObj->stock -= $item->quantity;
                $sizeObj->save();
            }

            // Kirim email konfirmasi
            Mail::to(Auth::user()->email)->send(new OrderConfirmationMail($order, Auth::user(), $item->product));
            $orders[] = $order;
        }

        // Hapus isi keranjang
        Cart::where('user_id', Auth::id())->delete();

        // Ambil order pertama untuk ditampilkan di halaman sukses
        $firstOrder = $orders[0];
        $paymentMethod = $request->payment_method;

        return view('checkout.payment-success', compact('firstOrder', 'paymentMethod'));
    }
}

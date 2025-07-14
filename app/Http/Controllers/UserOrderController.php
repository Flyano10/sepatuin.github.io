<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    // Menampilkan riwayat pesanan milik user yang sedang login
    public function index()
    {
        $orders = Order::with(['orderItems.product'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function show(\App\Models\Order $order)
    {
        $order->load(['orderItems.product']);
        return view('orders.show', compact('order'));
    }
}
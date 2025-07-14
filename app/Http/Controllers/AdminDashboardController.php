<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Total order
        $totalOrders = Order::count();
        // Total pendapatan (order paid saja)
        $totalRevenue = Order::where('status', 'paid')->sum('total_price');
        // Jumlah user
        $totalUsers = User::count();
        // Produk terlaris (berdasarkan quantity terjual)
        $topProducts = Order::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->where('status', 'paid')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->with('product')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('totalOrders', 'totalRevenue', 'totalUsers', 'topProducts'));
    }
} 
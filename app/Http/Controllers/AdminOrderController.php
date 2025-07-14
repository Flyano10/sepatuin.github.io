<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\OrderStatusUpdatedMail;
use Illuminate\Support\Facades\Mail;

class AdminOrderController extends Controller
{
    // ✅ Tampilkan daftar pesanan dengan filter status
    public function index(Request $request)
    {
        $query = Order::with(['user', 'product'])->latest();

        // ✅ Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        return view('admin.orders.index', compact('orders'));
    }

    // ✅ Update status pesanan
    public function updateStatus(Request $request, Order $order)
    {
        $order->update(['status' => $request->status]);
        // Kirim email ke user jika status berubah
        if ($order->user && $order->user->email) {
            Mail::to($order->user->email)->send(new OrderStatusUpdatedMail($order, $order->user, $request->status));
        }
        return redirect()->back()->with('success', 'Status pesanan diperbarui.');
    }

    // ✅ Cetak PDF semua pesanan
    public function exportPdf(Request $request)
    {
        $query = Order::with(['user', 'product'])->latest();

        // Filter juga di PDF jika diperlukan
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        $pdf = Pdf::loadView('admin.orders.pdf', compact('orders'))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-pesanan.pdf');
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.show', compact('order'));
    }
}

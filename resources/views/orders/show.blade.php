@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Detail Pesanan #{{ $order->id }}</h1>
    <div class="mb-4">
        <div><b>Status:</b> <span class="capitalize">{{ $order->status }}</span></div>
        <div><b>Tanggal Order:</b> {{ $order->created_at->format('d M Y H:i') }}</div>
        {{-- <div><b>Alamat:</b> {{ $order->address ?? '-' }}</div> --}}
    </div>
    <h2 class="font-semibold mb-2">Produk Dipesan:</h2>
    <table class="w-full mb-4 border">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-3 py-2 border">Produk</th>
                <th class="px-3 py-2 border">Size</th>
                <th class="px-3 py-2 border">Qty</th>
                <th class="px-3 py-2 border">Harga</th>
                <th class="px-3 py-2 border">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td class="px-3 py-2 border">{{ $item->product->name }}</td>
                <td class="px-3 py-2 border">{{ $item->size }}</td>
                <td class="px-3 py-2 border">{{ $item->quantity }}</td>
                <td class="px-3 py-2 border">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="px-3 py-2 border">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="font-bold text-right mb-2">Total: Rp {{ number_format($order->total_price ?? $order->total, 0, ',', '.') }}</div>
    <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-sm font-semibold mb-4">Print Invoice</button>
    <a href="{{ route('orders.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-full text-sm font-semibold mt-4 shadow transition">← Kembali ke Riwayat Pesanan</a>
</div>
@endsection 
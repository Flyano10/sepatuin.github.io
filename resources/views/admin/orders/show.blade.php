@extends('layouts.admin')

@section('content')
<div class="p-6 bg-white rounded shadow max-w-3xl mx-auto">
    <h1 class="text-xl font-bold mb-4">Detail Pesanan #{{ $order->id }}</h1>
    <div class="mb-4">
        <div><b>User:</b> {{ $order->user->name ?? '-' }} ({{ $order->user->email ?? '-' }})</div>
        <div><b>Status:</b>
            <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}" class="inline">
                @csrf
                @method('PATCH')
                <select name="status" onchange="this.form.submit()" class="border rounded px-2 py-1 text-sm">
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diproses" {{ $order->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ $order->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </form>
        </div>
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
    <a href="{{ route('admin.orders.index') }}" class="inline-block bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded-full text-sm font-semibold mt-4 shadow transition">← Kembali ke Daftar Pesanan</a>
</div>
@endsection 
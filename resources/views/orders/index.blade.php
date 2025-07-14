@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-6">Riwayat Pesanan Saya</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($orders->isEmpty())
            <div class="text-gray-600">Kamu belum memiliki pesanan.</div>
        @else
            <div class="bg-white shadow rounded p-4">
                <table class="w-full table-auto">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Tanggal</th>
                            <th class="px-4 py-2">Produk (Size x Qty)</th>
                            <th class="px-4 py-2">Total</th>
                            <th class="px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $index => $order)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $index + 1 }}</td>
                                <td class="px-4 py-2">{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td class="px-4 py-2">
                                    <ul class="list-disc ml-4">
                                        @foreach($order->orderItems as $item)
                                            <li>
                                                {{ $item->product->name }} <span class="text-xs text-gray-500">(Size {{ $item->size }} x{{ $item->quantity }})</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-4 py-2">Rp {{ number_format($order->total_price ?? $order->total, 0, ',', '.') }}</td>
                                <td class="px-4 py-2 capitalize">{{ $order->status }}</td>
                                <td class="px-4 py-2">
                                    <a href="{{ route('orders.show', $order->id) }}" class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">Lihat Detail</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection

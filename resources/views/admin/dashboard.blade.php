@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-extrabold text-indigo-700 mb-8 drop-shadow">Dashboard Admin</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
            <div class="text-4xl font-bold text-indigo-600 mb-2">{{ $totalOrders }}</div>
            <div class="text-gray-600 font-semibold">Total Order</div>
        </div>
        <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
            <div class="text-4xl font-bold text-green-600 mb-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <div class="text-gray-600 font-semibold">Total Pendapatan</div>
        </div>
        <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center">
            <div class="text-4xl font-bold text-blue-600 mb-2">{{ $totalUsers }}</div>
            <div class="text-gray-600 font-semibold">Jumlah User</div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-2xl font-bold text-indigo-700 mb-4">Produk Terlaris</h2>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total Terjual</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($topProducts as $i => $item)
                    <tr>
                        <td class="px-4 py-2">{{ $i+1 }}</td>
                        <td class="px-4 py-2 font-semibold">{{ $item->product->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item->total_sold }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-2 text-center text-gray-400">Belum ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection 
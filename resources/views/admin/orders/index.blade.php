@extends('layouts.admin')

@section('content')
    <div class="p-6 bg-white rounded shadow">
        <h1 class="text-xl font-semibold mb-4">Daftar Pesanan</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-2 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filter status --}}
        <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4 flex items-center gap-4">
            <div>
                <label for="status" class="mr-2">Filter Status:</label>
                <select name="status" id="status" onchange="this.form.submit()" class="border px-2 py-1 rounded text-sm">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            {{-- Tombol cetak PDF --}}
            <a href="{{ route('admin.orders.exportPdf', ['status' => request('status')]) }}"
                class="bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700">
                Cetak PDF
            </a>
        </form>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">#</th>
                    <th class="border px-4 py-2">Nama User</th>
                    <th class="border px-4 py-2">Produk</th>
                    <th class="border px-4 py-2">Qty</th>
                    <th class="border px-4 py-2">Total</th>
                    <th class="border px-4 py-2">Status</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td class="border px-4 py-2">{{ $order->id }}</td>
                        <td class="border px-4 py-2">{{ $order->user->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $order->product->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $order->quantity }}</td>
                        <td class="border px-4 py-2">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td class="border px-4 py-2">{{ ucfirst($order->status) }}</td>
                        <td class="border px-4 py-2">
                            <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                    class="border rounded px-2 py-1 text-sm">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="diproses" {{ $order->status === 'diproses' ? 'selected' : '' }}>Diproses
                                    </option>
                                    <option value="selesai" {{ $order->status === 'selesai' ? 'selected' : '' }}>Selesai
                                    </option>
                                </select>
                            </form>
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="ml-2 bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">Lihat Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-gray-500 py-4">Belum ada pesanan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

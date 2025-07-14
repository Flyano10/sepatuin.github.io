@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <h1 class="text-3xl font-extrabold text-center text-indigo-700 mb-8 drop-shadow">Pilih Metode Pembayaran</h1>

        {{-- Ringkasan belanja --}}
        <div class="bg-white rounded-2xl shadow-xl p-8 max-w-2xl mx-auto mb-8">
            <h2 class="text-2xl font-bold mb-6 text-indigo-700">Ringkasan Belanja</h2>

            <ul class="divide-y divide-gray-200 mb-6">
                @foreach ($cartItems as $item)
                    <li class="py-3 flex justify-between items-center">
                        <span class="font-medium">{{ $item->product->name }} <span class="text-xs text-gray-500">x{{ $item->quantity }}</span></span>
                        <span class="font-semibold">Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                    </li>
                @endforeach
            </ul>

            <div class="flex justify-between items-center font-bold text-xl border-t pt-6">
                <span>Total Pembayaran</span>
                <span class="text-indigo-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Pilihan Metode Pembayaran --}}
        <div class="bg-white rounded-2xl shadow-xl p-8 max-w-2xl mx-auto">
            <h2 class="text-2xl font-bold mb-6 text-indigo-700">Pilih Metode Pembayaran</h2>

            <form action="{{ route('checkout.process-payment') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    {{-- E-Wallet --}}
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-semibold text-lg mb-3 text-gray-800">E-Wallet</h3>
                        <div class="space-y-3">
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition">
                                <input type="radio" name="payment_method" value="gopay" class="mr-3 text-indigo-600" required>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white font-bold text-sm">GP</span>
                                    </div>
                                    <div>
                                        <div class="font-medium">GoPay</div>
                                        <div class="text-sm text-gray-500">Bayar dengan GoPay</div>
                                    </div>
                                </div>
                            </label>

                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition">
                                <input type="radio" name="payment_method" value="ovo" class="mr-3 text-indigo-600" required>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white font-bold text-sm">OV</span>
                                    </div>
                                    <div>
                                        <div class="font-medium">OVO</div>
                                        <div class="text-sm text-gray-500">Bayar dengan OVO</div>
                                    </div>
                                </div>
                            </label>

                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition">
                                <input type="radio" name="payment_method" value="dana" class="mr-3 text-indigo-600" required>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white font-bold text-sm">DA</span>
                                    </div>
                                    <div>
                                        <div class="font-medium">DANA</div>
                                        <div class="text-sm text-gray-500">Bayar dengan DANA</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Transfer Bank --}}
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-semibold text-lg mb-3 text-gray-800">Transfer Bank</h3>
                        <div class="space-y-3">
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition">
                                <input type="radio" name="payment_method" value="bca" class="mr-3 text-indigo-600" required>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white font-bold text-sm">BC</span>
                                    </div>
                                    <div>
                                        <div class="font-medium">Bank BCA</div>
                                        <div class="text-sm text-gray-500">Transfer BCA Virtual Account</div>
                                    </div>
                                </div>
                            </label>

                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition">
                                <input type="radio" name="payment_method" value="mandiri" class="mr-3 text-indigo-600" required>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white font-bold text-sm">MD</span>
                                    </div>
                                    <div>
                                        <div class="font-medium">Bank Mandiri</div>
                                        <div class="text-sm text-gray-500">Transfer Mandiri Virtual Account</div>
                                    </div>
                                </div>
                            </label>

                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition">
                                <input type="radio" name="payment_method" value="bni" class="mr-3 text-indigo-600" required>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white font-bold text-sm">BN</span>
                                    </div>
                                    <div>
                                        <div class="font-medium">Bank BNI</div>
                                        <div class="text-sm text-gray-500">Transfer BNI Virtual Account</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Kartu Kredit/Debit --}}
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-semibold text-lg mb-3 text-gray-800">Kartu Kredit/Debit</h3>
                        <div class="space-y-3">
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition">
                                <input type="radio" name="payment_method" value="credit_card" class="mr-3 text-indigo-600" required>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-800 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-white font-bold text-sm">CC</span>
                                    </div>
                                    <div>
                                        <div class="font-medium">Kartu Kredit</div>
                                        <div class="text-sm text-gray-500">Visa, Mastercard, JCB</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="mt-8 flex flex-col md:flex-row justify-between gap-4">
                    <a href="{{ route('checkout.index') }}"
                        class="bg-gray-200 text-gray-700 px-6 py-3 rounded-full font-semibold hover:bg-gray-300 transition text-sm shadow text-center">
                        ← Kembali ke Checkout
                    </a>

                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold px-8 py-3 rounded-full shadow-lg transition-all duration-200">
                        Bayar Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection 
@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-14">
        <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-xl p-10">
            <h1 class="text-4xl font-extrabold mb-6 text-indigo-700 text-center drop-shadow">Hubungi Kami</h1>
            <p class="text-gray-700 mb-6 text-center text-lg">
                Jika kamu memiliki pertanyaan, saran, atau ingin bekerjasama dengan kami, silakan hubungi kami melalui email atau formulir di bawah ini.
            </p>
            <ul class="text-gray-700 mb-8 text-center">
                <li class="mb-1"><strong>Email:</strong> support@sepatuin.com</li>
                <li class="mb-1"><strong>Instagram:</strong> @sepatuin.id</li>
                <li><strong>WhatsApp:</strong> +62 812-3456-7890</li>
            </ul>
            <form action="#" method="POST" class="space-y-5 max-w-md mx-auto">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama</label>
                    <input type="text" name="name" class="w-full border border-gray-300 rounded-full p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400" placeholder="Nama lengkap">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" class="w-full border border-gray-300 rounded-full p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400" placeholder="Alamat email aktif">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Pesan</label>
                    <textarea name="message" rows="4" class="w-full border border-gray-300 rounded-2xl p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400" placeholder="Tulis pesan kamu..."></textarea>
                </div>
                <button type="submit" class="bg-yellow-300 hover:bg-yellow-400 text-indigo-900 font-bold px-8 py-3 rounded-full shadow-lg transition-all duration-200 w-full">
                    Kirim Pesan
                </button>
            </form>
        </div>
    </div>
@endsection

@component('mail::message')
# Terima kasih sudah berbelanja di Sepatuin!

Halo, {{ $user->name }}.

Pesanan kamu sudah kami terima dan sedang diproses.

**Detail Pesanan:**
- Produk: {{ $product->name }}
- Jumlah: {{ $order->quantity }}
- Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}

Kami akan segera memproses pesananmu.  
Terima kasih atas kepercayaanmu!

Salam,
Sepatuin
@endcomponent

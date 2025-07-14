<x-mail::message>
# Status Pesanan Anda Diperbarui

Halo, {{ $user->name }}!

Status pesanan #{{ $order->id }} Anda telah berubah menjadi:
**{{ ucfirst($statusBaru) }}**

## Ringkasan Pesanan
@foreach($order->orderItems as $item)
- {{ $item->product->name }} (Size {{ $item->size }}) x{{ $item->quantity }}
@endforeach

**Total:** Rp {{ number_format($order->total_price ?? $order->total, 0, ',', '.') }}

Terima kasih telah berbelanja di {{ config('app.name') }}!

<x-mail::button :url="url('/orders/' . $order->id)">
Lihat Detail Pesanan
</x-mail::button>

Salam,
{{ config('app.name') }}
</x-mail::message>

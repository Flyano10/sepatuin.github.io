<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;

class Cart extends Model
{
    use HasFactory;

    /**
     * Field yang boleh diisi secara massal
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'size',
    ];

    /**
     * Relasi ke produk (setiap item keranjang milik satu produk)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relasi ke user (setiap item keranjang dimiliki oleh satu user)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

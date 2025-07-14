<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Cart;

class Product extends Model
{
    use HasFactory;

    // Field yang boleh diisi massal (mass assignment)
    protected $fillable = [
        'name',
        'description',
        'price',
        'image',
        'stock',
        'category_id',
    ];

    /**
     * Relasi ke model Cart
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }

    public function images()
    {
        return $this->hasMany(\App\Models\ProductImage::class);
    }

    public function sizes()
    {
        return $this->hasMany(\App\Models\ProductSize::class);
    }
}

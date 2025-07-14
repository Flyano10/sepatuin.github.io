<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Product;

class ReviewController extends Controller
{
    // Simpan atau update review
    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();
        $review = Review::updateOrCreate(
            [
                'user_id' => $userId,
                'product_id' => $productId,
            ],
            [
                'rating' => $request->rating,
                'review' => $request->review,
            ]
        );

        return back()->with('success', 'Review berhasil disimpan!');
    }

    // Hapus review
    public function destroy($productId)
    {
        Review::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();
        return back()->with('success', 'Review berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'komentar' => 'required|string',
            'rating' => 'required|numeric|min:0|max:5',
        ]);

        if ($validasi->fails()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        $review = Review::create([
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'komentar' => $request->komentar,
            'rating' => $request->rating,
        ]);
    
        return response()->json([
            'message' => 'Review berhasil dibuat',
            'data' => $review
        ], 201);
    }

    public function validasi($id) {
        $validasi = review::findOrFail($id);

        $validasi->update([
            'validasi' => 'sudah', 
        ]);

        if (!$validasi) {
            return response()->json([
                'massage' => 'Tidak dapat melakukan validasi data.'
            ], 400);
        }

        return response()->json($validasi, 201);
    }

    public function edit(Request $request, $id) {
        $review = Review::findOrFail($id);
        if ($review !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review->update([
            'komentar' => $request->komentar,
            'perubahan' => 'ya',
            'rating' => $request->rating,
        ]);

        return response()->json(['message' => 'Review berhasil diperbarui', 201]);
    }

    public function delete($id) {
        $review = review::findOrFail($id);

        $review->delete();
        return response()->json(['message' => 'The user review is deleted successfuly']);
    }
}

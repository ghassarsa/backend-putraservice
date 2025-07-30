<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
public function store(Request $request)
{
    $deviceToken = $request->cookie('comment_token') ?? Str::uuid()->toString();

    $review = Review::create([
        'name' => $request->name,
        'email' => $request->email,
        'komentar' => $request->komentar,
        'rating' => $request->rating,
        'device_token' => $deviceToken,
    ]);

    Cookie::queue('comment_token', $deviceToken, 60 * 24 * 10);

    return response()->json([
        'message' => 'Review berhasil dibuat',
        'data' => $review
    ]);
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
        $deviceToken = $request->cookie('comment_token');

        if (!$deviceToken) {
            return response()->json(['message' => 'Perubahan tidak diizinkan'], 403);
        }

        $review = Review::where('id', $id)->where('device_token', $deviceToken)->first();

        if (!$review) {
            return response()->json(['message' => 'Tidak diizinkan mengedit review ini'], 403);
        }

        $review->update([
            'komentar' => $request->komentar,
            'perubahan' => 'ya',
            'rating' => $request->rating,
        ]);

        return response()->json([
            'message' => 'Review berhasil diperbarui',
            'data' => $review
        ]);
    }

    public function delete($id) {
        $review = review::findOrFail($id);

        $review->delete();
        return response()->json(['message' => 'The user review is deleted successfuly']);
    }
}

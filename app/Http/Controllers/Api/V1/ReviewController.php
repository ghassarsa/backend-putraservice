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
    public function show() {
    $reviews = Review::with('user')
        ->where('validasi', 'sudah')
        ->orderBy('updated_at', 'desc')
        ->get();

    // Ubah format data yang dikirim ke frontend supaya mudah dipakai
    $data = $reviews->map(function($review) {
        return [
            'id' => $review->id,
            'komentar' => $review->komentar,
            'rating' => $review->rating,
            'updated_at' => $review->updated_at->diffForHumans(),
            'name' => $review->user->name ?? 'User',
            'avatar' => $review->user
                ? (
                    $review->user->google_id
                    ? $review->user->avatar 
                    : ($review->user->avatar ? asset('storage/' . $review->user->avatar) : null)
                  )
                : null,
        ];
    });

    return response()->json($data);
    }

    public function store(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'komentar' => 'required|string',
            'rating' => 'required|numeric|min:0|max:5',
        ]);

        if ($validasi->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validasi->errors()
            ], 422);
        }

        $review = Review::create([
            'komentar' => $request->komentar,
            'rating' => $request->rating,
            'user_id' => auth()->id(),
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
        if ($review->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review->update([
            'komentar' => $request->komentar,
            'perubahan' => 'ya',
            'rating' => $request->rating,
        ]);

        return response()->json(['message' => 'Review berhasil diperbarui'], 200);
    }

    public function delete($id) {
        $review = review::findOrFail($id);

        if ($review->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $review->delete();
        return response()->json(['message' => 'The user review is deleted successfuly'], 200);
    }
}

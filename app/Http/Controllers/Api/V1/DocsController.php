<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Settings_docs;
use App\Models\Docs;
use Illuminate\Http\Request;

class DocsController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->query('category_id');
        $offset = intval($request->query('offset', 0));

        $setting = null;
        if ($categoryId) {
            $setting = Settings_docs::where('category_id', $categoryId)->first();
        } else {
            $setting = Settings_docs::whereNull('category_id')->first();
        }
        $limit = $setting ? intval($setting->value) : 30;

        $query = Docs::query();
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $total = $query->count();

        $docs = $query->orderBy('id', 'desc')
                      ->skip($offset)
                      ->take($limit)
                      ->get(['id', 'title', 'description', 'image', 'category_id']);

        $data = $docs->map(function ($doc) {
            return [
                'id' => $doc->id,
                'title' => $doc->title,
                'description' => $doc->description,
                'image' => $doc->image,
                'category_id' => $doc->category_id,
            ];
        });

        return response()->json([
            'data' => $data,
            'limit' => $limit,
            'total' => $total,
        ]);
    }
}

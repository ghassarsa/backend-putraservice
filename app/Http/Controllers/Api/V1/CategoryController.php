<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($name) {
        $name = str_replace('-', ' ', $name);
        $category = category::where('name', $name)->firstOrFail();

        return response()->json($category);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Docs;
use Illuminate\Http\Request;

class DocsController extends Controller
{
    public function index() {
        return response()->json(Docs::all());
    }
}

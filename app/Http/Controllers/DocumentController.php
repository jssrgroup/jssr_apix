<?php

namespace App\Http\Controllers;

use App\Http\Resources\DocumentResource;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $departments = Document::all();
        return response()->json([
            'message' => 'Department List',
            'data' => DocumentResource::collection($departments)
        ], 200);
    }

    public function getAllByDep($depId)
    {
        $departments = Document::where('ref_dep_id', $depId)->get();
        return response()->json([
            'message' => 'Department List',
            'data' => DocumentResource::collection($departments)
        ], 200);
    }
}

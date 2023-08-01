<?php

namespace App\Http\Controllers;

use App\Http\Resources\DepartmemtResource;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return response()->json([
            'message' => 'Department List',
            'data' => DepartmemtResource::collection($departments)
        ], 200);
    }
    public function getById($id)
    {
        $department = Department::find($id);
        return response()->json([
            'message' => 'Department Data',
            'data' => $department
        ], 200);
    }
}

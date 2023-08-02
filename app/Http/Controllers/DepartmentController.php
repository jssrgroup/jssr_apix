<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
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

    public function store(DepartmentRequest $request)
    {

        $validated = $request->safe()->all();

        $input = array_filter($validated, function ($value) {
            return $value !== null;
        });

        $department = Department::create($input);

        return response()->json([
            "success" => true,
            "message" => "Department created successfully.",
            "data" => $department
        ]);
    }

    public function update(DepartmentRequest $request, $id)
    {
        $validated = $request->safe()->all();

        $input = array_filter($validated, function ($value) {
            return $value !== null;
        });

        $department = Department::find($id);
        $department->update($input);
        

        return response()->json([
            "success" => true,
            "message" => "Department updated successfully.",
            "data" => $department
        ]);
    }

    public function destroy($id)
    {
        Department::destroy($id);

        return response()->json([
            "success" => true,
            "message" => "Department delete successfully.",
        ]);
    }
}

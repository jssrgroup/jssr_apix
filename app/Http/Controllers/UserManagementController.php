<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserManagementRequest;
use App\Http\Requests\UpdateUserManagementRequest;
use App\Http\Resources\UserManagementResource;
use App\Models\UserManagement;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userManagements = UserManagement::all();
        return response()->json([
            'message' => 'User Management List',
            'data' => UserManagementResource::collection($userManagements)
        ], 200);
    }

    public function getById($id)
    {
        $userManagement = UserManagement::find($id);
        return response()->json([
            'message' => 'User Management Data',
            'data' => new UserManagementResource($userManagement),
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserManagementRequest $request)
    {

        $validated = $request->safe()->all();

        $input = array_filter($validated, function ($value) {
            return $value !== null;
        });

        $userManagement = UserManagement::create($input);

        return response()->json([
            "success" => true,
            "message" => "Document Type created successfully.",
            "data" => $userManagement
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserManagement  $userManagement
     * @return \Illuminate\Http\Response
     */
    public function show(UserManagement $userManagement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserManagement  $userManagement
     * @return \Illuminate\Http\Response
     */
    public function edit(UserManagement $userManagement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserManagement  $userManagement
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserManagementRequest $request, $id)
    {
        $validated = $request->safe()->all();

        $input = array_filter($validated, function ($value) {
            return $value !== null;
        });

        $userManagement = UserManagement::find($id);
        $userManagement->update($input);
        

        return response()->json([
            "success" => true,
            "message" => "Document Type updated successfully.",
            "data" => $userManagement
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserManagement  $userManagement
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        UserManagement::destroy($id);

        return response()->json([
            "success" => true,
            "message" => "User Management delete successfully.",
        ]);
    }
}

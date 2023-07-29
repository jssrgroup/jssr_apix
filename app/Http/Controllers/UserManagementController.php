<?php

namespace App\Http\Controllers;

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
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, UserManagement $userManagement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserManagement  $userManagement
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserManagement $userManagement)
    {
        //
    }
}

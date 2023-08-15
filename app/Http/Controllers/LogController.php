<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Http\Requests\StoreLogRequest;
use App\Http\Requests\UpdateLogRequest;
use App\Http\Resources\LogResource;
use App\Http\Resources\UserAdminResource;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // // Get the authorization header
        // $authorizationHeader = $request->header('Authorization');

        // // Extract the token from the header (e.g., "Bearer <token>")
        // $token = str_replace('Bearer ', '', $authorizationHeader);

        // $user = new UserAdminResource(auth('useradmins')->user());

        // return response()->json([
        //     "success" => true,
        //     "message" => "Log created successfully.",
        //     "token" => $token,
        //     "id" => $user['INDX'],
        //     "user" => $user,
        // ]);

        $logs = Log::orderBy('created_at', 'desc')->get();
        return response()->json([
            "success" => true,
            "message" => "Log List.",
            "data" => LogResource::collection($logs),
        ]);
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
     * @param  \App\Http\Requests\StoreLogRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLogRequest $request)
    {
        $validated = $request->safe()->all();

        $log = Log::create($validated);

        return response()->json([
            "success" => true,
            "message" => "Log created successfully.",
            "data" => $log
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function show(Log $log)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function edit(Log $log)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLogRequest  $request
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLogRequest $request, Log $log)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Log  $log
     * @return \Illuminate\Http\Response
     */
    public function destroy(Log $log)
    {
        //
    }
}

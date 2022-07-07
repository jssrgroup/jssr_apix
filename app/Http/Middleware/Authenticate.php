<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // return $request->expectsJson();
        // return response()->json([
        //     'message' => 'Unauthorize 401',
        // ], 401);
        if (! $request->expectsJson()) {
            return route('api/login');
        }
        // if (! ($request->expectsJson() || collect($request->route()->middleware())->contains('api'))) {
        //     return route('login');
        // }
    }
}

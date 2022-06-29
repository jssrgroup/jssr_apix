<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserPassResource;
use App\Models\UserPass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserPassController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:userpasss', ['except' => ['login', 'register']]);
    }

    public function demo()
    {
        $user = UserPass::where('log_user', 'ongarg')
            ->where('log_password', 'rungro')
            ->first();
        return response()->json([
            'message' => 'User Pass',
            'user' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        // $request->request->add(['log_user' => $request['username']]);
        // $request->request->add(['log_password' => $request['password']]);
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $user = UserPass::where('log_user', $request->username)
            ->where('log_password', $request->password)
            ->first();

        if ($user == null) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

        try {
            $token = JWTAuth::fromUser($user);

            return response()->json(["status" => 200, "data" => [
                "access_token" => $token,
                "user" => $user
            ]]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(['error' => 'invalid_credentials'], 401);
    }

    public function logout()
    {
        auth('userpasss')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh()
    {
        return $this->createNewToken(auth('userpasss')->refresh());
    }

    public function userProfile() {
        return new UserPassResource(auth('userpasss')->user());
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('userpasss')->factory()->getTTL() * 60,
            'user' => auth('userpasss')->user()
        ]);
    }
}

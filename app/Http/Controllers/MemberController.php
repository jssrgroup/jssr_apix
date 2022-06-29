<?php

namespace App\Http\Controllers;

use App\Http\Resources\MemberResource;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class MemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:members', ['except' => ['login', 'register']]);
    }
    
    public function demo(){
        $user = Member::where('user_name', 'sanchai')
            ->where('password', 'san77')
            ->first();
        return response()->json([
            'message' => 'User Member',
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
        $user = Member::where('user_name', $request->username)
            ->where('password', $request->password)
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
        auth('members')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh()
    {
        return $this->createNewToken(auth('members')->refresh());
    }

    public function userProfile() {
        return new MemberResource(auth('members')->user());
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('members')->factory()->getTTL() * 60,
            'user' => auth('members')->user()
        ]);
    }
}

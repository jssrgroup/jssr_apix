<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserAdminResource;
use App\Models\UserAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:useradmins', ['except' => ['demo','login', 'register']]);
    }

    public function demo(){
        $admin = UserAdmin::where('username', 'tanakphong')
            ->where('password', 'Phong@12345')
            ->first();
        return response()->json([
            'message' => 'User Admin',
            'user' => $admin
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
        $user = UserAdmin::where('username', $request->username)
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
        auth('useradmins')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh()
    {
        return $this->createNewToken(auth('useradmins')->refresh());
    }

    public function userProfile() {
        return new UserAdminResource(auth('useradmins')->user());
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('useradmins')->factory()->getTTL() * 60,
            'user' => auth('useradmins')->user()
        ]);
    }
}

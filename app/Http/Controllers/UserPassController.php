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
        $this->middleware('auth:userpasss', ['except' => ['index','demo', 'login', 'register']]);
    }

    
    public function index()
    {
        $userp = UserPass::all();
        
        return $userp;
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

    /**
     * @OA\Post(
     * path="/api/user/login",
     * summary="User Login",
     * description="Login User Here",
     * operationId="userAuthLogin",
     * tags={"User"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"username", "password"},
     *               @OA\Property(property="username", type="text"),
     *               @OA\Property(property="password", type="password")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Login Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Login Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
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
    /**
     * @OA\Post(
     * path="/api/user/logout",
     * summary="User Logout",
     * description="User Logout",
     * operationId="UserLogout",
     * tags={"User"},
     * security={ {"bearer_token": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Logout Successfully",
     *          @OA\JsonContent()
     *       ),
     * )
     */
    public function logout()
    {
        auth('userpasss')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * @OA\Post(
     * path="/api/user/refresh",
     * summary="Refresh Token",
     * description="Refresh Token",
     * operationId="userRefreshToken",
     * tags={"User"},
     * security={ {"bearer_token": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Get User Successfully",
     *          @OA\JsonContent()
     *       ),
     * )
     */
    public function refresh()
    {
        return $this->createNewToken(auth('userpasss')->refresh());
    }
    /**
     * @OA\Get(
     * path="/api/user/user-profile",
     * summary="Get User Detail",
     * description="Get User Detail",
     * operationId="GetUserDetail",
     * tags={"User"},
     * security={ {"bearer_token": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Get User Successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="int", example=1),
     *              @OA\Property(property="name", type="string", example="developer"),
     *              @OA\Property(property="email", type="string", example="developer@jssr.co.th"),
     *              @OA\Property(property="email_verified_at", type="string", example="01/01/2022"),
     *          )
     *       ),
     * )
     */
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

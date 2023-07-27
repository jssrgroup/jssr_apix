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
    public function index()
    {
        $members = Member::all();
        return response()->json([
            'message' => 'Member List',
            'data' => $members
        ], 200);
    }
    public function demo()
    {
        $user = Member::where('user_name', 'sanchai')
            ->where('password', 'san77')
            ->first();
        return response()->json([
            'message' => 'User Member',
            'user' => $user
        ], 200);
    }
    /**
     * @OA\Post(
     * path="/api/member/login",
     * summary="Member Login",
     * description="Login Member Here",
     * operationId="memberLogin",
     * tags={"Member"},
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
    /**
     * @OA\Post(
     * path="/api/member/logout",
     * summary="Member Logout",
     * description="Member Logout",
     * operationId="MemberLogout",
     * tags={"Member"},
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
        auth('members')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * @OA\Post(
     * path="/api/member/refresh",
     * summary="Refresh Token",
     * description="Refresh Token",
     * operationId="MemberRefreshToken",
     * tags={"Member"},
     * security={ {"bearer_token": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Get Member Successfully",
     *          @OA\JsonContent()
     *       ),
     * )
     */
    public function refresh()
    {
        return $this->createNewToken(auth('members')->refresh());
    }
    /**
     * @OA\Get(
     * path="/api/member/user-profile",
     * summary="Get Member Detail",
     * description="Get Member Detail",
     * operationId="GetMemberDetail",
     * tags={"Member"},
     * security={ {"bearer_token": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Get Member Successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="int", example=1),
     *              @OA\Property(property="name", type="string", example="developer"),
     *              @OA\Property(property="email", type="string", example="developer@jssr.co.th"),
     *              @OA\Property(property="email_verified_at", type="string", example="01/01/2022"),
     *          )
     *       ),
     * )
     */
    public function userProfile()
    {
        return new MemberResource(auth('members')->user());
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('members')->factory()->getTTL() * 60,
            'user' => auth('members')->user()
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserAdminResource;
use App\Models\UserAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:useradmins', ['except' => ['index','demo', 'login', 'register']]);
    }

    public function index()
    {
        $admins = UserAdmin::all();
        return response()->json([
            'message' => 'Admin List',
            'data' => UserAdminResource::collection($admins)
        ], 200);
    }

    public function demo()
    {
        $admin = UserAdmin::where('username', 'tanakphong')
            ->where('password', 'Phong@12345')
            ->first();
        return response()->json([
            'message' => 'User Admin',
            'user' => $admin
        ], 200);
    }
    /**
     * @OA\Post(
     * path="/api/admin/login",
     * summary="Admin Login",
     * description="Login Admin Here",
     * operationId="adminAuthLogin",
     * tags={"Admin"},
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
        $user = UserAdmin::where('username', $request->username)
            ->where('password', $request->password)
            ->first();

        if ($user == null) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

        try {

            UserAdmin::where('INDX', $user['INDX'])->update([
                'LAST_LOGIN' => time(),
            ]);

            $user = UserAdmin::where('INDX', $user['INDX'])->first();

            $token = JWTAuth::fromUser($user);

            return response()->json(["status" => 200, "data" => [
                "access_token" => $token,
                "user" => new UserAdminResource($user)
            ]]);
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(['error' => 'invalid_credentials'], 401);
    }
    /**
     * @OA\Post(
     * path="/api/admin/logout",
     * summary="Admin Logout",
     * description="Admin Logout",
     * operationId="AdminLogout",
     * tags={"Admin"},
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
        auth('useradmins')->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * @OA\Post(
     * path="/api/admin/refresh",
     * summary="Refresh Token",
     * description="Refresh Token",
     * operationId="adminRefreshToken",
     * tags={"Admin"},
     * security={ {"bearer_token": {} }},
     *      @OA\Response(
     *          response=200,
     *          description="Get Admin Successfully",
     *          @OA\JsonContent()
     *       ),
     * )
     */
    public function refresh()
    {
        return $this->createNewToken(auth('useradmins')->refresh());
    }
    /**
     * @OA\Get(
     * path="/api/admin/user-profile",
     * summary="Get Admin Detail",
     * description="Get Admin Detail",
     * operationId="GetAdminDetail",
     * tags={"Admin"},
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
    public function userProfile()
    {
        return new UserAdminResource(auth('useradmins')->user());
    }

    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('useradmins')->factory()->getTTL() * 60,
            'user' => auth('useradmins')->user()
        ]);
    }
}

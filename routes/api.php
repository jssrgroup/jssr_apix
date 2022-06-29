<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\UserPassController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('api/login', function(){
    return response()->json([
        'message' => 'Unauthorize 401',
    ], 401);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

Route::group(['prefix' => 'admin', 'middleware' => ['assign.guard:useradmins']], function () {
    Route::get('/demo', [UserAdminController::class, 'demo']);
    Route::post('/login', [UserAdminController::class, 'login']);
    Route::post('/logout', [UserAdminController::class, 'logout']);
    Route::post('/refresh', [UserAdminController::class, 'refresh']);
    Route::get('/user-profile', [UserAdminController::class, 'userProfile']);
});

Route::group(['prefix' => 'user', 'middleware' => 'assign.guard:userpasss'], function () {
    Route::get('/demo', [UserPassController::class, 'demo']);
    Route::post('/login', [UserPassController::class, 'login']);
    Route::post('/logout', [UserPassController::class, 'logout']);
    Route::post('/refresh', [UserPassController::class, 'refresh']);
    Route::get('/user-profile', [UserPassController::class, 'userProfile']);
});

Route::group(['prefix' => 'member', 'middleware' => 'assign.guard:members'], function () {
    Route::get('/demo', [MemberController::class, 'demo']);
    Route::post('/login', [MemberController::class, 'login']);
    Route::post('/logout', [MemberController::class, 'logout']);
    Route::post('/refresh', [MemberController::class, 'refresh']);
    Route::get('/user-profile', [MemberController::class, 'userProfile']);
});

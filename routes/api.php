<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PersonalDataController;
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

Route::get('login', function(){
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
    Route::get('/all', [UserAdminController::class, 'index']);
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

// Route::group(['prefix' => 'customer', 'middleware' => ['jwt.auth.member']], function () {
Route::group(['prefix' => 'customer'], function () {
    Route::get('/', [CustomerController::class, 'index']);
    Route::get('/{id}', [CustomerController::class, 'show']);
    Route::post('/', [CustomerController::class, 'store']);
    Route::put('/{id}', [CustomerController::class, 'update']);
    Route::post('/acceptconsent/{id}', [CustomerController::class,'acceptConsent']);
    Route::put('/acceptconsent/{id}', [CustomerController::class,'updateConsent']);
});

Route::group(['prefix' => 'userpass/customer', 'middleware' => ['jwt.auth.userpass']], function () {
    Route::get('/', [CustomerController::class, 'index']);
    Route::post('/', [CustomerController::class, 'store']);
});

Route::group(['prefix' => 'useradmin/customer', 'middleware' => ['jwt.auth.useradmin']], function () {
    Route::get('/', [CustomerController::class, 'index']);
    Route::post('/', [CustomerController::class, 'store']);
});

Route::apiResources(['pdata' => PersonalDataController::class]);
Route::get('pdata/edit/{id}', [PersonalDataController::class,'getEditbyId']);
Route::get('pdata-orderby', [PersonalDataController::class,'getOrderBy']);
Route::put('pdata-orderby', [PersonalDataController::class,'updateOrderBy']);
Route::get('pdata-acceptconsent', [PersonalDataController::class,'getAcceptConsent']);
Route::put('pdata-acceptconsent', [PersonalDataController::class,'updateAcceptConsent']);

Route::post('encrypt', [PersonalDataController::class,'encrypt']);
Route::post('decrypt', [PersonalDataController::class,'decrypt']);

Route::get('image-upload', [ ImageUploadController::class, 'index' ])->name('image');
Route::get('image-upload-all', [ ImageUploadController::class, 'getAll' ])->name('image.all');
Route::get('image-upload/{name}', [ ImageUploadController::class, 'imageUpload' ])->name('image.upload');
Route::post('image-upload', [ ImageUploadController::class, 'imageUploadPost' ])->name('image.upload.post');

Route::get('images', [ImageController::class, 'index'])->name('images');
Route::get('images', [ImageController::class, 'index'])->name('images');

Route::get('emails', [EmailController::class, 'email']);

// Route::get('customer/{id}', [CustomerController::class, 'show']);

// Route::group(['middleware' => 'auth:api'], function () {
//     Route::apiResources([
//         'user' => 'API\UserController',
//         'posts' => 'API\PostController'
//     ]);
// });

// Route::group(['middleware' => 'auth:api', 'namespace' => 'API'], function () {
//     Route::apiResources([
//         'user' => 'UserController',
//         'posts' => 'PostController'
//     ]);
// });
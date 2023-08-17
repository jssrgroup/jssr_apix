<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\ImageUploadCusController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PersonalDataController;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\UserPassController;
use App\Models\Document;
use App\Models\DocumentType;

// Function
function parseLocale()
{
    $locale = request()->segment(3);
    $locales = config('app.locales');
    $default = config('app.fallback_locale');

    if (in_array($locale, $locales)) {
        app()->setLocale($locale);
        return $locale;
        // }else{
        //     app()->setLocale($default);
        //     return $locale;
    }
}

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

Route::get('login', function () {
    return response()->json([
        'message' => 'Unauthorize 401',
    ], 401);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::get('/all', [AuthController::class, 'index']);
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
    Route::get('/all', [UserPassController::class, 'index']);
    Route::get('/demo', [UserPassController::class, 'demo']);
    Route::post('/login', [UserPassController::class, 'login']);
    Route::post('/logout', [UserPassController::class, 'logout']);
    Route::post('/refresh', [UserPassController::class, 'refresh']);
    Route::get('/user-profile', [UserPassController::class, 'userProfile']);
});

Route::group(['prefix' => 'member', 'middleware' => 'assign.guard:members'], function () {
    Route::get('/all', [MemberController::class, 'index']);
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
    Route::post('/update/{id}', [CustomerController::class, 'update']);
    Route::delete('/{id}', [CustomerController::class, 'destroy']);
    Route::post('/delete/{id}', [CustomerController::class, 'destroy']);
    Route::post('/acceptconsent/{id}', [CustomerController::class, 'acceptConsent']);
    Route::put('/acceptconsent/{id}', [CustomerController::class, 'updateConsent']);
    Route::get('/attachment/{id}', [CustomerController::class, 'attachment']);
    Route::get('/attachmentCus/{id}', [CustomerController::class, 'attachmentCus']);
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
Route::get('pdata/edit/{id}', [PersonalDataController::class, 'getEditbyId']);
Route::get('pdata-orderby', [PersonalDataController::class, 'getOrderBy']);
Route::put('pdata-orderby', [PersonalDataController::class, 'updateOrderBy']);
Route::get('pdata-acceptconsent', [PersonalDataController::class, 'getAcceptConsent']);
Route::put('pdata-acceptconsent', [PersonalDataController::class, 'updateAcceptConsent']);

Route::post('encrypt', [PersonalDataController::class, 'encrypt']);
Route::post('decrypt', [PersonalDataController::class, 'decrypt']);

Route::get('image-upload', [ImageUploadController::class, 'index'])->name('image');
Route::get('image-upload-all', [ImageUploadController::class, 'getAll'])->name('image.all');
Route::get('image-upload/{name}', [ImageUploadController::class, 'imageUpload'])->name('image.upload');
Route::post('image-upload', [ImageUploadController::class, 'imageUploadPost'])->name('image.upload.post');
Route::delete('image-upload/{id}', [ImageUploadController::class, 'deleteFile']);
Route::post('image-upload/delete/{id}', [ImageUploadController::class, 'deleteFile']);

Route::get('image-upload-cus', [ImageUploadCusController::class, 'index']);
Route::get('image-upload-cus-all', [ImageUploadCusController::class, 'getAll']);
Route::get('image-upload-cus/{name}', [ImageUploadCusController::class, 'imageUpload']);
Route::post('image-upload-cus', [ImageUploadCusController::class, 'imageUploadPost']);
Route::delete('image-upload-cus/{id}', [ImageUploadCusController::class, 'deleteFile']);
Route::post('image-upload-cus/delete/{id}', [ImageUploadCusController::class, 'deleteFile']);

Route::get('images', [ImageController::class, 'index'])->name('images');
Route::get('images', [ImageController::class, 'index'])->name('images');

Route::get('emails', [EmailController::class, 'email']);
Route::get('testRsa', [PersonalDataController::class, 'testRsa']);

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
Route::group([
    'prefix' => 'v2',
    'middleware' => 'jwt.auth.useradmin'
], function ($router) {
    Route::group([
        'prefix' => parseLocale(), //'{locale}',//
        // 'middleware' => 'setlocale'
    ], function ($router) {
        Route::get('/', function (Request $request) {
            // return config('app.locales');
            // return $request->segment(3);
            // $locale = request()->segment(3);
            // $locales = config('app.locales');
            // $default = config('app.fallback_locale');

            // if ($locale !== $default && in_array($locale, $locales)) {
            // //     app()->setLocale($locale);
            // return $locale;
            // }
            // return $locale !== $default && in_array($locale, $locales);
            return trans('validation.required') . app()->currentLocale();
        });
        Route::group([
            'prefix' => 'department'
        ], function ($router) {
            Route::get('/all', [DepartmentController::class, 'index']);
            Route::get('/{id}', [DepartmentController::class, 'getById']);
            Route::post('/add', [DepartmentController::class, 'store']);
            Route::put('/{id}', [DepartmentController::class, 'update']);
            Route::post('/{id}/update', [DepartmentController::class, 'update']);
            Route::delete('/{id}', [DepartmentController::class, 'destroy']);
            Route::post('/{id}/delete', [DepartmentController::class, 'destroy']);
        });
        Route::group([
            'prefix' => 'documentType'
        ], function ($router) {
            Route::get('/all', [DocumentTypeController::class, 'index']);
            Route::get('/{id}', [DocumentTypeController::class, 'getById']);
            Route::get('/{depId}/all', [DocumentTypeController::class, 'getDocTypeByDep']);
            Route::get('/{depId}/parent', [DocumentTypeController::class, 'getDocTypeByDepNotDoc']);
            Route::get('/{depId}/all/{docId}', [DocumentTypeController::class, 'getDocTypeByDepAndDoc']);
            Route::post('/add', [DocumentTypeController::class, 'store']);
            Route::put('/{id}', [DocumentTypeController::class, 'update']);
            Route::post('/{id}/update', [DocumentTypeController::class, 'update']);
            Route::delete('/{id}', [DocumentTypeController::class, 'destroy']);
            Route::post('/{id}/delete', [DocumentTypeController::class, 'destroy']);
            Route::get('/{id}/parentDesc', function ($id) {
                // $docType = DocumentType::where('id', $id)->first();
                // return isset($docType['desc']) ? $docType['desc'] : null;
                $docType = DocumentType::find($id);
                return $docType['desc'];
            });
        });
        Route::group([
            'prefix' => 'document'
        ], function ($router) {
            Route::get('/all', [DocumentController::class, 'index']);
            Route::get('/{depId}/all', [DocumentController::class, 'getAllByDep']);
            Route::get('/{name}', [DocumentController::class, 'imageUpload'])->middleware('jwt.auth.useradmin');
            Route::post('/', [DocumentController::class, 'imageUploadPost']);
            Route::delete('/{id}', [DocumentController::class, 'deleteFile']);
            Route::post('/{id}/delete', [DocumentController::class, 'deleteFile']);
            Route::group([
                'prefix' => 'report'
            ], function ($router) {
                Route::get('/expire', [DocumentController::class, 'getAllExpire']);
                Route::get('/expire/{depId}/all', [DocumentController::class, 'getAllExpireByDepId']);
                Route::get('/expire/{id}', [DocumentController::class, 'getAllExpireById']);
            });
        });
        Route::group([
            'prefix' => 'userManagement'
        ], function ($router) {
            Route::get('/all', [UserManagementController::class, 'index']);
            Route::get('/{id}', [UserManagementController::class, 'getById']);
            Route::post('/add', [UserManagementController::class, 'store']);
            Route::put('/{id}', [UserManagementController::class, 'update']);
            Route::post('/{id}/update', [UserManagementController::class, 'update']);
            Route::delete('/{id}', [UserManagementController::class, 'destroy']);
            Route::post('/{id}/delete', [UserManagementController::class, 'destroy']);
        });
        Route::group([
            'prefix' => 'log'
        ], function ($router) {
            Route::get('/', [LogController::class, 'index']);
            // Route::get('/', [LogController::class, 'index'])->middleware('jwt.auth.useradmin');
            Route::post('/', [LogController::class, 'store']);
            Route::get('/doc/{id}', function($id){
                $doc = Document::find($id);
                return $doc['image_name'];
            });
        });
        Route::group([
            'prefix' => 'customer'
        ], function ($router) {
            Route::get('/hello', function () {
                return config('app.locales');
            });
        });
        Route::group([
            'prefix' => 'admin'
        ], function ($router) {
            Route::get('/all', [UserAdminController::class, 'index']);
            Route::get('/{id}', [UserAdminController::class, 'getById']);
        });
        Route::group([
            'prefix' => 'member'
        ], function ($router) {
            Route::get('/all', [MemberController::class, 'index']);
            Route::get('/{id}', [MemberController::class, 'getById']);
        });
    });
});

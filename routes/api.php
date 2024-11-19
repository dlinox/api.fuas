<?php

use App\Http\Controllers\AttentionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CookieSessionsController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkerController;
use App\Models\CookieSessions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/sign-in', [AuthController::class, 'signIn']);
    Route::post('/sign-out', [AuthController::class, 'signOut'])->middleware('auth:sanctum');
    Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
});

//positions
Route::group(['prefix' => 'positions'], function () {
    Route::post('/load-data-table', [PositionController::class, 'loadDataTable'])->middleware('auth:sanctum');
    Route::post('/save', [PositionController::class, 'save'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [PositionController::class, 'delete'])->middleware('auth:sanctum');
    Route::get('/load-select', [PositionController::class, 'loadSelect'])->middleware('auth:sanctum');
});

//offices
Route::group(['prefix' => 'offices'], function () {
    Route::post('/load-data-table', [OfficeController::class, 'loadDataTable'])->middleware('auth:sanctum');
    Route::post('/save', [OfficeController::class, 'save'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [OfficeController::class, 'delete'])->middleware('auth:sanctum');
    Route::get('/load-select', [OfficeController::class, 'loadSelect'])->middleware('auth:sanctum');
});

//workers
Route::group(['prefix' => 'workers'], function () {
    Route::post('/load-data-table', [WorkerController::class, 'loadDataTable'])->middleware('auth:sanctum');
    Route::post('/save', [WorkerController::class, 'save'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [WorkerController::class, 'delete'])->middleware('auth:sanctum');
    Route::get('/load-select', [WorkerController::class, 'loadSelect'])->middleware('auth:sanctum');
});

//users
Route::group(['prefix' => 'users'], function () {
    Route::post('/load-data-table', [UserController::class, 'loadDataTable'])->middleware('auth:sanctum');
    Route::post('/save', [UserController::class, 'save'])->middleware('auth:sanctum');
    Route::delete('/delete/{id}', [UserController::class, 'delete'])->middleware('auth:sanctum');
    Route::get('/load-select', [UserController::class, 'loadSelect'])->middleware('auth:sanctum');
    //allPermissions
    Route::get('/all-permissions', [UserController::class, 'allPermissions'])->middleware('auth:sanctum');
    //assignPermissions
    Route::post('/assign-permissions', [UserController::class, 'assignPermissions'])->middleware('auth:sanctum');
});

//categories
Route::group(['prefix' => 'categories'], function () {
    Route::post('/load-data-table', [CategoryController::class, 'loadDataTable'])->middleware('auth:sanctum');
    Route::post('/save', [CategoryController::class, 'save'])->middleware('auth:sanctum');
});

//services
Route::group(['prefix' => 'services'], function () {
    Route::post('/load-data-table', [ServiceController::class, 'loadDataTable'])->middleware('auth:sanctum');
    Route::post('/save', [ServiceController::class, 'save'])->middleware('auth:sanctum');
});


Route::get('/sis/get-data/{sessionId}', [ AttentionController::class, 'getDataSIS' ])->middleware('auth:sanctum');

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('/token', [TokenController::class, 'createToken']);
Route::get('/token/{token}', [TokenController::class, 'verifytoken']);
Route::get('/users', [UserController::class, 'show']);

Route::get('/test', function(){
    $user = $_SERVER['AUTH_USER'];

    dd($user);
});

Route::controller(AuthController::class)
    ->group(function () {
        Route::post('/login', 'login');
    });

Route::controller(EmployeeController::class)
    ->prefix('/employees')
    ->middleware(['verify.cookie', 'auth:api', 'role:admin'])
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/update', 'update');
        Route::post('/import', 'import');
    });

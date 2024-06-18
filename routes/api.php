<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// Route::controller(AuthController::class)
//     ->group(function () {
//         Route::post('/login', 'login');
//     });

Route::controller(EmployeeController::class)
    ->prefix('/employees')
    // ->middleware(['verify.cookie', 'auth:api', 'role:admin|task_manager'])
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/update', 'update');
    });



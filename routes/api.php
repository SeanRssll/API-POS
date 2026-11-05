<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\UserControllers\UserController;

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
Route::prefix('v1')->group(function () {
    Route::post('login', [LoginController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::middleware([
            'permission:users.create'
        ])->post('register', [LoginController::class, 'register']);
        
        Route::middleware([
             'permission:users.update.role'
        ])->patch('assignRole', [UserController::class, 'assignRole']);

        Route::middleware([
             'permission:users.remove.role'
        ])->post('removeRole', [UserController::class, 'removeRole']);
        
        Route::middleware([
            'permission:users.view'
        ])->get('user-dropdown', [UserController::class, 'userDropdown']);
    });
    // Route::resource('posts', PostController::class);
//     permission sample
//     Route::middleware([
//     'permission:sales.create,sales.refund'
// ])->resource('/sales', SaleController::class);
});

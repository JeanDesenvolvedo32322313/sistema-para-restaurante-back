<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return response()->json([
        'API' => "API REST-FULL para a utilização de restaurante",
        'version' => "1.0"
    ]);
});

Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware(['admin'])->group(function () {

    Route::post('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);

    Route::prefix('users')->controller(UserController::class)->group(function () {
        Route::get('read', 'read')->name('users.read');
        Route::post('create', 'create')->name('users.create');
        Route::get('list_roles', 'listRoles')->name('users.listRoles');
        Route::post('update/{id}', 'update')->name('users.update');
    });
});

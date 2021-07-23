<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RepoController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthApiController::class, 'login']);
Route::post('register', [AuthApiController::class, 'register']);

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', [AuthApiController::class, 'logout']);

    Route::get('user', [AuthApiController::class, 'getAuthUser']);

    Route::get('all-events', [EventController::class, 'index']);
    Route::post('events', [EventController::class, 'store']);
    Route::put('update-event/{id}', [EventController::class, 'update']);
    Route::delete('delete-event/{id}', [EventController::class, 'destroy']);

    Route::get('all-repos', [RepoController::class, 'index']);
    Route::post('repos/{id}', [RepoController::class, 'store']);
    Route::put('update-repo/{id}', [RepoController::class, 'update']);
    Route::delete('delete-repo/{id}', [RepoController::class, 'destroy']);
});


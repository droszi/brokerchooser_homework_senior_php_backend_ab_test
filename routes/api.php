<?php

use App\Http\Controllers\Api\AbTestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::put('/v1/abtest/{id}/start', [AbTestController::class, 'start']);
Route::put('/v1/abtest/{id}/stop', [AbTestController::class, 'stop']);

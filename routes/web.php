<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbTestDemoController;
use App\Http\Controllers\AbTestAdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/abtestdemo', AbTestDemoController::class);
Route::get('/abtest/demo', AbTestDemoController::class);
Route::get('/abtest/admin', AbTestAdminController::class);

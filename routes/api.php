<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\KategoriController;

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


Route::group(['middleware' => ['jwt.verify', 'role:admin']], function () {
    Route::resource('barang', BarangController::class)->only(['store', 'update', 'destroy']);
    Route::resource('kategori', KategoriController::class)->only(['store', 'update', 'destroy']);
});

//User tidak perlu login untuk GET dan SHOW
Route::resource('barang', BarangController::class)->only(['index', 'show']);
Route::resource('kategori', KategoriController::class)->only(['index', 'show']);




Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'register']);
    Route::post('me', [AuthController::class, 'me']);
});

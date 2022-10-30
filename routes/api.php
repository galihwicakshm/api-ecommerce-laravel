<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\DetailorderController;

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
    Route::resource('transaksi', TransaksiController::class)->only(['store', 'update', 'destroy']);
    Route::resource('cart', CartController::class)->only(['store', 'update', 'destroy']);
    Route::delete('carts/{id_user}', [CartController::class, 'destroyAll']);
    Route::post('check', [CheckoutController::class, 'checkout']);
    Route::put('cartsupdate/{id_cart}', [CartController::class, 'updateCart']);
    Route::post('cartsinc/{id_cart}', [CartController::class, 'updateIncrement']);
    Route::post('cartsdec/{id_cart}', [CartController::class, 'updateDecrement']);
    // Route::resource('checkout', CheckoutController::class);
});

//User tidak perlu login untuk GET dan SHOW
Route::resource('transaksi', TransaksiController::class)->only(['index', 'show']);
Route::resource('cart', CartController::class);
Route::resource('barang', BarangController::class)->only(['index', 'show']);
Route::resource('kategori', KategoriController::class)->only(['index', 'show']);
Route::resource('detailorder', DetailorderController::class)->only('index');
Route::get('cobaredirect', [RedirectController::class, 'index'])->name('cobaredirect');



Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'register']);
    Route::post('me', [AuthController::class, 'me']);
});

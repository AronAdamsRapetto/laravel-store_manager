<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
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

Route::controller(ProductController::class)->group(function () {
    Route::get('/product', 'index');
    Route::get('/product/{id}', 'show')->middleware('consultProduct');
    Route::post('/product', 'store')->middleware('verifyProduct');
    Route::put('/product/{id}', 'update')->middleware(['verifyProduct', 'consultProduct']);
    Route::delete('/product/{id}', 'destroy')->middleware('consultProduct');
});

Route::controller(SaleController::class)->group(function () {
    Route::post('/sale', 'store')->middleware(['verifySale', 'consultProduct']);
    Route::get('/sale', 'index');
    Route::get('/sale/{id}', 'show');
    Route::delete('/sale/{id}', 'destroy');
    Route::put('/sale/{id}', 'update')->middleware(['verifySale', 'consultProduct']);;
});

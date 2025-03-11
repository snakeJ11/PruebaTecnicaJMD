<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPriceController;
use App\Http\Controllers\CurrencyController;
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


// Rutas de la API
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'listProducts']); 
    Route::post('/createProduct', [ProductController::class, 'createProduct']); 
    Route::get('/{id}', [ProductController::class, 'getById']); 
    Route::put('/updateProduct/{id}', [ProductController::class, 'updateProduct']); 
    Route::delete('/deleteProduct/{id}', [ProductController::class, 'deleteProduct']); 
    Route::get('/{id}/prices', [ProductPriceController::class, 'getPrices']); 
    Route::post('/{id}/createPrices', [ProductPriceController::class, 'createPrices']); 
});


Route::prefix('currencies')->group(function () {
    Route::get('/', [CurrencyController::class, 'index']); 
    Route::post('/', [CurrencyController::class, 'store']); 
    Route::get('/{id}', [CurrencyController::class, 'show']); 
    Route::put('/{id}', [CurrencyController::class, 'update']); 
    Route::delete('/{id}', [CurrencyController::class, 'destroy']); 
});
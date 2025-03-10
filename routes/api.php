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
    Route::get('/getProducts', [ProductController::class, 'index']); // Obtener lista de productos
    Route::get('/getProduct/{id}', [ProductController::class, 'getProduct']); // Obtener lista de productos
    Route::post('/createProduct', [ProductController::class, 'store']); // Crear un nuevo producto
    Route::get('/getById/{id}', [ProductController::class, 'show']); // Obtener un producto por ID
    Route::put('/UpdateProduct/{id}', [ProductController::class, 'update']); // Actualizar un producto
    Route::delete('/deleteProduct/{id}', [ProductController::class, 'destroy']); // Eliminar un producto
    Route::get('/getPrices/{id}', [ProductPriceController::class, 'index']); // Obtener lista de precios de un producto
    Route::post('/createPrice/{id}', [ProductPriceController::class, 'store']); // Crear un nuevo precio para un producto
});


Route::prefix('currencies')->group(function () {
    Route::get('/', [CurrencyController::class, 'index']); // Listar todas las divisas
    Route::post('/', [CurrencyController::class, 'store']); // Crear una nueva divisa
    Route::get('/{id}', [CurrencyController::class, 'show']); // Obtener una divisa por ID
    Route::put('/{id}', [CurrencyController::class, 'update']); // Actualizar una divisa
    Route::delete('/{id}', [CurrencyController::class, 'destroy']); // Eliminar una divisa
});
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Currency;
use App\Models\ProductPrice;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ProductPriceController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products/{id}/prices",
     *     summary="Obtener los precios de un producto en diferentes monedas",
     *     tags={"Precios de Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de precios obtenida exitosamente",
     *         @OA\JsonContent(type="array", @OA\Items(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="product_id", type="integer", example=1),
     *             @OA\Property(property="original_price", type="number", format="float", example=100.5),
     *             @OA\Property(property="converted_price", type="number", format="float", example=120.5),
     *             @OA\Property(property="currency", type="object", 
     *                 @OA\Property(property="name", type="string", example="Dólar"),
     *                 @OA\Property(property="symbol", type="string", example="$"),
     *                 @OA\Property(property="exchange_rate", type="number", format="float", example=1.2)
     *             )
     *         ))
     *     )
     * )
     */
    public function getPrices($productId)
    {
        try
        {
            if (!is_numeric($productId)) 
            {
                return response()->json(['error' => 'ID de producto inválido'], 400);
            }
            
            $product = Product::find($productId);
            if (!$product) 
            {
                return response()->json(['error' => 'Producto no encontrado'], 404);
            }
            
            // Obtener los precios del producto
            $prices = ProductPrice::with('currency')
            ->where('product_id', $productId)
            ->get()
            ->map(function ($price) 
            {
                return [
                    'id' => $price->id,
                    'product_id' => $price->product_id,
                    'original_price' => $price->price,
                            'converted_price' => number_format($price->price * $price->currency->exchange_rate, 3, '.', ''),
                            'currency' => 
                            [
                                'name' => $price->currency->name,
                                'symbol' => $price->currency->symbol,
                                'exchange_rate' => $price->currency->exchange_rate,
                            ],
                        ];
            });
                    
                    return response()->json($prices);
        }
        catch (\Exception $e) 
        {
            return response()->json(['error' => 'Ocurrió un error en el servidor'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/products/{id}/createPrices",
     *     summary="Crear un precio para un producto",
     *     tags={"Precios de Productos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="price", type="number", format="float", example=100.5),
     *             @OA\Property(property="currency_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Precio creado exitosamente"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Petición incorrecta, faltan parámetros"
     *     )
     * )
     */
    public function createPrices(Request $request, $productId)
    {
        try
        {
            if (!is_numeric($productId)) 
            {
                return response()->json(['error' => 'ID de producto inválido'], 400);
            }

            $validated = $request->validate([
                'price' => 'required|numeric|min:0',
                'currency_id' => 'required|exists:currencies,id',
            ]);
            

            $product = Product::find($productId);
            if (!$product) {
                return response()->json(['error' => 'Producto no encontrado'], 404);
            }
            

            $price = ProductPrice::create(array_merge($validated, ['product_id' => $productId]));
            
            return response()->json($price, 201);
        }   
        catch (\Exception $e) 
        {
            return response()->json(['error' => 'Ocurrió un error en el servidor'], 500);
        }
    }
}

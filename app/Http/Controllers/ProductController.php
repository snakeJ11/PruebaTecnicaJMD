<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;

    /**
 * @OA\Info(
 *     title="Prueba Tecnica Jeferson Mercedes",
 *     version="1.0.0",
 *     description="Documentación de la API",
 *     @OA\Contact(
 *         email="jeferson.md0212@gmail.com"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 */
class ProductController extends Controller
{
        /**
         * @OA\Get(
         *     path="/api/products/",
         *     summary="Lista todos los productos",
         *     tags={"Productos"},
         *     @OA\Response(
         *         response=200,
         *         description="Lista de productos obtenida exitosamente"
         *     )
         * )
         */
    public function listProducts()
    {
        try{return response()->json(Product::all());}
        catch (\Exception $e) 
        {
            return response()->json(['error' => 'Ocurrió un error en el servidor'], 500);
        }
    }

        /**
         * @OA\Post(
         *     path="/api/products/createProduct",
         *     summary="Crear un nuevo producto",
         *     tags={"Productos"},
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             required={"name", "description", "price","currency_id","tax_cost","manufacturing_cost"},
         *             @OA\Property(property="name", type="string", example="Producto A"),
         *             @OA\Property(property="description", type="string", example="Descripción del producto A"),
         *             @OA\Property(property="price", type="number", format="float", example=100.5),
         *             @OA\Property(property="currency_id", type="number", format="int", example=1),
         *             @OA\Property(property="tax_cost", type="number", format="float", example=100.5),
         *             @OA\Property(property="manufacturing_cost", type="number", format="float", example=900.5),
         *         )
         *     ),
         *     @OA\Response(
         *         response=201,
         *         description="Producto creado exitosamente"
         *     ),
         *     @OA\Response(
         *         response=400,
         *         description="Error de validación"
         *     )
         * )
         */
    public function createProduct(Request $request)
    {
        try
        {
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'description' => 'required|string',
                    'price' => 'required|numeric|min:0',
                    'currency_id' => 'required|numeric|min:0',
                    'tax_cost' => 'required|numeric|min:0',
                    'manufacturing_cost' => 'required|numeric|min:0',
                ]);
                
                $product = Product::create($validated);
                return response()->json($product, 201);
        }
        catch (\Exception $e) 
        {
            return response()->json(['error' => 'Ocurrió un error en el servidor'], 500);
        }
    }

        /**
         * @OA\Get(
         *     path="/api/products/{id}",
         *     summary="Obtener un producto por ID",
         *     tags={"Productos"},
         *     @OA\Parameter(
         *         name="id",
         *         in="path",
         *         required=true,
         *         @OA\Schema(type="integer")
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Producto obtenido exitosamente"
         *     ),
         *     @OA\Response(
         *         response=404,
         *         description="Producto no encontrado"
         *     )
         * )
         */
    public function getById($id)
    {
        try
        {
            if (!is_numeric($id)) 
            {
                return response()->json(['error' => 'ID de producto inválido'], 400);
            }

            $product = Product::findOrFail($id);
            return response()->json($product);
        }
        catch (\Exception $e) 
        {
            return response()->json(['error' => 'Ocurrió un error en el servidor'], 500);
        }
    }

        /**
         * @OA\Put(
         *     path="/api/products/updateProduct/{id}",
         *     summary="Actualizar un producto existente",
         *     tags={"Productos"},
         *     @OA\Parameter(
         *         name="id",
         *         in="path",
         *         required=true,
         *         @OA\Schema(type="integer")
         *     ),
         *     @OA\RequestBody(
         *         required=true,
         *         @OA\JsonContent(
         *             @OA\Property(property="name", type="string", example="Producto B"),
         *             @OA\Property(property="description", type="string", example="Descripción actualizada"),
         *             @OA\Property(property="price", type="number", format="float", example=120.0)
         *         )
         *     ),
         *     @OA\Response(
         *         response=200,
         *         description="Producto actualizado exitosamente"
         *     ),
         *     @OA\Response(
         *         response=400,
         *         description="Error de validación"
         *     ),
         *     @OA\Response(
         *         response=404,
         *         description="Producto no encontrado"
         *     )
         * )
         */
    public function updateProduct(Request $request, $id)
    {
        try
        {
            if (!is_numeric($id)) 
            {
                return response()->json(['error' => 'ID de producto inválido'], 400);
            }
                $product = Product::findOrFail($id);
                
                $validated = $request->validate([
                    'name' => 'required|string|max:255',
                    'description' => 'required|string',
                    'price' => 'required|numeric|min:0',
                ]);
                
                $product->update($validated);
                return response()->json($product);
        }
        catch (\Exception $e) 
        {
            return response()->json(['error' => 'Ocurrió un error en el servidor'], 500);
        }
    }

        /**
         * @OA\Delete(
         *     path="/api/products/deleteProduct/{id}",
         *     summary="Eliminar un producto",
         *     tags={"Productos"},
         *     @OA\Parameter(
         *         name="id",
         *         in="path",
         *         required=true,
         *         @OA\Schema(type="integer")
         *     ),
         *     @OA\Response(
         *         response=204,
         *         description="Producto eliminado exitosamente"
         *     ),
         *     @OA\Response(
         *         response=404,
         *         description="Producto no encontrado"
         *     )
         * )
         */
    public function deleteProduct($id)
    {
        try
        {
            if (!is_numeric($id)) 
            {
                return response()->json(['error' => 'ID de producto inválido'], 400);
            }

            $product = Product::findOrFail($id);
            $product->delete();
            
            return response()->json(['message' => 'Producto borrado con éxito'], 204);
        } 
        catch (\Exception $e) 
        {
            return response()->json(['error' => 'Ocurrió un error en el servidor'], 500);
        }
    }
}

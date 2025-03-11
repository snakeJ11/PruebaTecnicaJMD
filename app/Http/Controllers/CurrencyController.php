<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/currencies",
     *     summary="Obtener todas las divisas",
     *     tags={"Currencies"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de divisas obtenida exitosamente",
     *         @OA\JsonContent(type="array", @OA\Items(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Dólar"),
     *             @OA\Property(property="symbol", type="string", example="$"),
     *             @OA\Property(property="exchange_rate", type="number", format="float", example=1.0)
     *         ))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Currency::all(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/currencies",
     *     summary="Crear una nueva divisa",
     *     tags={"Currencies"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Euro"),
     *             @OA\Property(property="symbol", type="string", example="€"),
     *             @OA\Property(property="exchange_rate", type="number", format="float", example=1.2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Divisa creada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:currencies',
            'symbol' => 'required|string|unique:currencies',
            'exchange_rate' => 'required|numeric|min:0',
        ]);

        $currency = Currency::create($request->all());

        return response()->json($currency, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/currencies/{id}",
     *     summary="Obtener una divisa por ID",
     *     tags={"Currencies"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Divisa encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Dólar"),
     *             @OA\Property(property="symbol", type="string", example="$"),
     *             @OA\Property(property="exchange_rate", type="number", format="float", example=1.0)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Divisa no encontrada"
     *     )
     * )
     */
    public function show($id)
    {
        $currency = Currency::find($id);

        if (!$currency) {
            return response()->json(['message' => 'Divisa no encontrada'], 404);
        }

        return response()->json($currency, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/currencies/{id}",
     *     summary="Actualizar una divisa",
     *     tags={"Currencies"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Libra Esterlina"),
     *             @OA\Property(property="symbol", type="string", example="£"),
     *             @OA\Property(property="exchange_rate", type="number", format="float", example=1.5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Divisa actualizada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Divisa no encontrada"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $currency = Currency::find($id);

        if (!$currency) {
            return response()->json(['message' => 'Divisa no encontrada'], 404);
        }

        $request->validate([
            'name' => 'string|unique:currencies,name,' . $id,
            'symbol' => 'string|unique:currencies,symbol,' . $id,
            'exchange_rate' => 'numeric|min:0',
        ]);

        $currency->update($request->all());

        return response()->json($currency, 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/currencies/{id}",
     *     summary="Eliminar una divisa",
     *     tags={"Currencies"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Divisa eliminada exitosamente"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Divisa no encontrada"
     *     )
     * )
     */
    public function destroy($id)
    {
        $currency = Currency::find($id);

        if (!$currency) {
            return response()->json(['message' => 'Divisa no encontrada'], 404);
        }

        $currency->delete();

        return response()->json(['message' => 'Divisa eliminada'], 200);
    }
}

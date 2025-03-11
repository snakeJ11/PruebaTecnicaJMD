<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{

    public function index()
    {
        return response()->json(Currency::all(), 200);
    }

  
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
     * Mostrar una divisa específica.
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
     * Actualizar una divisa.
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
     * Eliminar una divisa.
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

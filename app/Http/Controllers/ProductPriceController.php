<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Currency;
use App\Models\ProductPrice;
use Illuminate\Database\Eloquent\Model;


class ProductPriceController extends Controller
{
    public function index($productId)
    {
        $prices = ProductPrice::with('currency')
            ->where('product_id', $productId)
            ->get()
            ->map(function ($price) {
                return [
                    'id' => $price->id,
                    'product_id' => $price->product_id,
                    'original_price' => $price->price,
                    'converted_price' => number_format($price->price * $price->currency->exchange_rate, 3, '.', ''),
                    'currency' => [
                        'name' => $price->currency->name,
                        'symbol' => $price->currency->symbol,
                        'exchange_rate' => $price->currency->exchange_rate,
                    ],
                ];
            });
    
        return response()->json($prices);
    }

    public function store(Request $request, $productId)
    {
        $price = ProductPrice::create(array_merge($request->all(), ['product_id' => $productId]));
        return response()->json($price, 201);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Currency;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;


class ProductController extends Controller
{
    public function index()
    {
        return response()->json(Product::all());
    }
    public function getProduct($productId)
    {
        return response()->json(Product::where('id', $productId)->get());
    }

    public function store(Request $request)
    {
        $product = Product::create($request->all());
        return response()->json($product, 201);
    }

    public function show($id)
    {
        return response()->json(Product::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return response()->json($product);
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
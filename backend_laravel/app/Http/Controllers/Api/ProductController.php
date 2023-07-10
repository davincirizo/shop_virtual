<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();
        return $products;
    }


    public function store(Request $request)
    {
        $product = Product::create($request->all());
    }


    public function show(Product $product)
    {
        return response()->json($product);
    }


    public function update(Request $request, Product $product)
    {
        $product->update($request->all());
    }


    public function destroy(Product $product)
    {
        $product->delete();
    }
}

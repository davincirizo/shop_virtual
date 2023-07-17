<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{

    public function index()
    {
        $productes = Product::covertJson();
//
        return ProductResource::collection($productes);
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

    public function search(Category $category){
        $products = Product::searchBy($category);
        return ProductResource::collection($products);

    }
}

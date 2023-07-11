<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Controllers\Api\ProductController;


class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        $products = Product::all();
        for($i = 0; $i < count($categories); $i++) {
            $product_ext = $products->where('category_id','=',$categories[$i]->id);
            $count = count($product_ext);
            $categories[$i]->qty_product = $count;
        }
        // return CategoryResource::collection($categories);
        return response()->json($categories);
    }


    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->all());
        return (new CategoryResource($category))->additional(['msg'=>'Categoria creada correctamente']);
    }


    public function show(Category $category)
    {

        return response()->json($category);
    }


    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->all());
        return (new CategoryResource($category))
        ->additional(['msg'=>'Categoria actualizada correctamente'])
        ->response()
        ->setStatusCode(202);

    }


    public function destroy(Category $category)
    {
        // $category->delete();
        // return response()->json([
        //     'res' => True,
        //     'msg' => 'Categoria Eliminada Correctamente'
        // ],200);
        $category->delete();
        return (new CategoryResource($category))->additional(['msg'=>'Categoria eliminada correctamente']);
    }
}

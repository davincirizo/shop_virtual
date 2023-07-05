<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;


class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();
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

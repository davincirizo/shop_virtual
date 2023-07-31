<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Storage;




class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::setCategory();
        return response()->json($categories);
    }


    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->all());
        if($request->file('images')) {
            $file = $request->file('images');
            foreach ($file as $qdoc => $eldocu) {
                $url = Storage::put('categories', $eldocu);
                $category->images()->create(['url'=>$url]);
            }
        }
        return (new CategoryResource($category))->additional(['msg'=>'Categoria creada correctamente']);
    }


    public function show(Category $category)
    {
        if($category->images()->count() > 0){
            $category->imagenes = $category->images()->get();
        }
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
        if($category->images()->count() > 0) {
            $category->images()->delete();
        }
        $category->delete();

        return (new CategoryResource($category))->additional(['msg'=>'Categoria eliminada correctamente']);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name','price','stock','category_id'];

    public function category(){
        return $this->belongTo('App\Models\Category');
    }

    public static function covertJson(){
        $categories = Category::all();
        $products = Product::all();
         for($i = 0; $i < count($products); $i++) {
             if($products[$i]->category_id) {
                 $category = $categories->where('id', '=', $products[$i]->category_id)->first();
             }

             if ($products[$i]->category_id) {
                 $products[$i]->category_name = $category->name;
             }
             }
          return $products;
    }
    public static function searchBy(Category $category){
        $products = Product::all();
        $products_select = $products->where('category_id','=',$category->id);
        return $products_select;
    }

}

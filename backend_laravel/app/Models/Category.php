<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Category extends Model
{
    protected $fillable = ['name','description'];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];


    use HasFactory;

    public function products(){
        return $this->hasMany('App\Models\Product');
    }

    public static function setCategory(){
        $categories = Category::all();
        $products = Product::all();

        for($i = 0; $i < count($categories); $i++) {
            $product_ext = $products->where('category_id','=',$categories[$i]->id);
            $count = count($product_ext);
            $categories[$i]->qty_product = $count;
            if($categories[$i]->images()->count() > 0){
                $categories[$i]->imagenes = $categories[$i]->images()->get();
            }

        }
        return $categories;
    }

    public function images(){
        return $this->morphMany('App\Models\Image','imageable');
    }


}

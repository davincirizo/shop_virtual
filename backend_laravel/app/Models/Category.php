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

    public function getQty_ProductAttribute($value){
       return $value + 8;
    }

    public function setQty_ProductAttribute($value){
        $this->attributes['qty_product'] = $value + 8;
    }

    use HasFactory;

    public function products(){
        return $this->hasMany('App\Models\Product');
    }




}

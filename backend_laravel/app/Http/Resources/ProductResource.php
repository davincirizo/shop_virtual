<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->resource->id,
            'name'=>$this->resource->name,
            'price'=>$this->resource->price,
            'stock'=>$this->resource->stock,
            'category_id' => [
            'id' => $this->resource->category_id,
                'name' => $this->resource->category_name,
            ],


        ];
    }
}

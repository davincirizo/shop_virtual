<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'identificador' => $this->id,
            'nombre' => $this->name,
            'fecha de creacion' => $this->created_at->format('d-m-y'),
            'fecha de actualizacion' => $this->updated_at,



        ];
    }
    public function with(Request $request){
        return [
            'res' => True
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'color' => $this->color,
            'backgroundColor' => $this->backgroundColor,
            'category_icon' => $this->category_icon,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'base_price' => $this->base_price,
            'min_order_quantity' => $this->min_order_quantity,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->toDateTimeString(),
            'category' => new ProductCategoryResource($this->whenLoaded('category')),
            'featured_image' => new ProductImageResource($this->whenLoaded('featuredImage', $this->whenLoaded('firstImage'))),
            'images' => ProductImageResource::collection($this->whenLoaded('images')),
            'options' => ProductOptionResource::collection($this->whenLoaded('options')),
        ];
    }
}
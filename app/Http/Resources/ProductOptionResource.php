<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductOptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->whenLoaded('attributeValue', function () {
                return $this->attributeValue->attribute->name;
            }),
            'price_adjustment' => $this->price_adjustment,
            'value' => new AttributeValueResource($this->whenLoaded('attributeValue')),
        ];
    }
}

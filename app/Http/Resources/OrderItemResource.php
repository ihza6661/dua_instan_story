<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'sub_total' => $this->sub_total,
            'product' => new ProductResource($this->product),
            'variant' => new ProductVariantResource($this->variant),
            'custom_data' => $this->customData->form_data ?? null,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'total_amount' => $this->total_amount,
            'shipping_address' => $this->shipping_address,
            'order_status' => $this->order_status,
            'created_at' => $this->created_at->toDateTimeString(),
            'items' => OrderItemResource::collection($this->items),
            'custom_data' => $this->custom_data,
        ];
    }
}

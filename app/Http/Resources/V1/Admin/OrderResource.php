<?php

namespace App\Http\Resources\V1\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->customer_id,
            'user_full_name' => $this->customer->full_name ?? null,
            'status' => $this->order_status,
            'total_amount' => $this->total_amount,
            'created_at' => $this->created_at,
            'shipping_address' => $this->shippingAddress ? $this->shippingAddress->full_address : null,
            'billing_address' => $this->billingAddress ? $this->billingAddress->full_address : null,
            'order_items' => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
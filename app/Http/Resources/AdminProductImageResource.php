<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminProductImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if ($this->resource === null) {
            return [];
        }

        return [
            'id' => $this->id,
            'image' => $this->image,
            'alt_text' => $this->alt_text,
            'is_featured' => $this->is_featured,
        ];
    }
}
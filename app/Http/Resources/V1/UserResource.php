<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name, // ✅ Pastikan baris ini ada dan benar
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'role' => $this->role,
        ];
    }
}
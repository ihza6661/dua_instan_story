<?php

namespace App\Http\Requests\Api\V1\Admin\ProductOption;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'price_adjustment' => ['sometimes', 'required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'price_adjustment.required' => 'Penyesuaian harga wajib diisi.',
            'price_adjustment.integer' => 'Penyesuaian harga harus berupa angka.',
        ];
    }
}

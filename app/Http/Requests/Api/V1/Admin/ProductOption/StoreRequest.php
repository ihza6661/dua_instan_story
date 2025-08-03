<?php

namespace App\Http\Requests\Api\V1\Admin\ProductOption;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'attribute_value_id' => [
                'required',
                'integer',
                'exists:attribute_values,id',
                Rule::unique('product_options')->where('product_id', $this->route('product')->id)
            ],
            'price_adjustment' => ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'attribute_value_id.required' => 'Nilai atribut wajib dipilih.',
            'attribute_value_id.exists' => 'Nilai atribut tidak valid.',
            'attribute_value_id.unique' => 'Opsi ini sudah ditambahkan ke produk.',
            'price_adjustment.required' => 'Penyesuaian harga wajib diisi.',
        ];
    }
}

<?php

namespace App\Http\Requests\Api\V1\Cart;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $product = Product::find($this->input('product_id'));

        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', function ($attribute, $value, $fail) use ($product) {
                if ($product && $value < $product->min_order_quantity) {
                    $fail("Jumlah minimal pesanan untuk produk ini adalah {$product->min_order_quantity}.");
                }
            }],
            'options' => ['nullable', 'array'],
            'options.*' => ['integer', 'exists:product_options,id'],
            'add_ons' => ['nullable', 'array'],
            'add_ons.*' => ['integer', 'exists:add_ons,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Produk wajib dipilih.',
            'product_id.integer' => 'ID produk tidak valid.',
            'product_id.exists' => 'Produk yang dipilih tidak ditemukan.',

            'quantity.required' => 'Jumlah pesanan wajib diisi.',
            'quantity.integer' => 'Jumlah pesanan harus berupa angka.',
            'quantity.min' => 'Jumlah pesanan minimal 1 buah.',

            'options.array' => 'Data opsi harus dalam format array.',
            'options.*.integer' => 'ID opsi tidak valid.',
            'options.*.exists' => 'Salah satu opsi yang dipilih tidak tersedia untuk produk ini.',

            'add_ons.array' => 'Data item tambahan harus dalam format array.',
            'add_ons.*.integer' => 'ID item tambahan tidak valid.',
            'add_ons.*.exists' => 'Salah satu item tambahan yang dipilih tidak tersedia.',
        ];
    }
}

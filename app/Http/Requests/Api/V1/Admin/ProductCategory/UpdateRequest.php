<?php

namespace App\Http\Requests\Api\V1\Admin\ProductCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        $categoryId = $this->route('product_category')->id;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:100', Rule::unique('product_categories')->ignore($categoryId)],
            'description' => ['sometimes', 'nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.string' => 'Nama kategori harus berupa teks.',
            'name.max' => 'Nama kategori tidak boleh lebih dari 100 karakter.',
            'name.unique' => 'Nama kategori ini sudah ada.',
            'description.string' => 'Deskripsi harus berupa teks.',
        ];
    }
}

<?php

namespace App\Http\Requests\Api\V1\Admin\Attribute;

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
        $attributeId = $this->route('attribute')->id;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:100', Rule::unique('attributes')->ignore($attributeId)],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama atribut wajib diisi.',
            'name.unique' => 'Nama atribut ini sudah ada.',
        ];
    }
}

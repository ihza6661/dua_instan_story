<?php

namespace App\Http\Requests\Api\V1\Admin\AttributeValue;

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
        $attributeValue = $this->route('value');

        return [
            'value' => [
                'sometimes',
                'required',
                'string',
                'max:100',
                Rule::unique('attribute_values')
                    ->where('attribute_id', $attributeValue->attribute_id)
                    ->ignore($attributeValue->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'value.required' => 'Nilai atribut wajib diisi.',
            'value.unique' => 'Nilai ini sudah ada untuk atribut yang bersangkutan.',
        ];
    }
}

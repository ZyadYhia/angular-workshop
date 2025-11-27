<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Cat::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name_en' => ['required', 'string', 'max:255'],
            'name_ar' => ['required', 'string', 'max:255'],
            'active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name_en.required' => 'The English name field is required.',
            'name_en.max' => 'The English name must not exceed 255 characters.',
            'name_ar.required' => 'The Arabic name field is required.',
            'name_ar.max' => 'The Arabic name must not exceed 255 characters.',
            'active.boolean' => 'The active field must be true or false.',
        ];
    }
}

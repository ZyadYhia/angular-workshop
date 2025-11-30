<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Course::class);
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
            'img' => ['required', 'string', 'max:50'],
            'cat_id' => ['required', 'integer', 'exists:cats,id'],
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
            'img.required' => 'The image field is required.',
            'img.max' => 'The image must not exceed 50 characters.',
            'cat_id.required' => 'The category field is required.',
            'cat_id.exists' => 'The selected category does not exist.',
            'active.boolean' => 'The active field must be true or false.',
        ];
    }
}

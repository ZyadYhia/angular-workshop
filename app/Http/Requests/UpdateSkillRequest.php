<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSkillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $skill = $this->route('skill');

        return $this->user()->can('update', $skill);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name_en' => ['sometimes', 'string', 'max:255'],
            'name_ar' => ['sometimes', 'string', 'max:255'],
            'img' => ['sometimes', 'string', 'max:50'],
            'cat_id' => ['sometimes', 'integer', 'exists:cats,id'],
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
            'name_en.max' => 'The English name must not exceed 255 characters.',
            'name_ar.max' => 'The Arabic name must not exceed 255 characters.',
            'img.max' => 'The image must not exceed 50 characters.',
            'cat_id.exists' => 'The selected category does not exist.',
            'active.boolean' => 'The active field must be true or false.',
        ];
    }
}

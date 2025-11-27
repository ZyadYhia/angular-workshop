<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'desc_en' => ['sometimes', 'string'],
            'desc_ar' => ['sometimes', 'string'],
            'img' => ['sometimes', 'string', 'max:50'],
            'questions_no' => ['sometimes', 'integer', 'min:1', 'max:127'],
            'difficulty' => ['sometimes', 'integer', 'min:1', 'max:127'],
            'duration_mins' => ['sometimes', 'integer', 'min:1', 'max:32767'],
            'skill_id' => ['sometimes', 'integer', 'exists:skills,id'],
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
            'questions_no.integer' => 'The number of questions must be an integer.',
            'difficulty.integer' => 'The difficulty must be an integer.',
            'duration_mins.integer' => 'The duration must be an integer.',
            'skill_id.exists' => 'The selected skill does not exist.',
            'active.boolean' => 'The active field must be true or false.',
        ];
    }
}

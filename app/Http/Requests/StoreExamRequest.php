<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamRequest extends FormRequest
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
            'name_en' => ['required', 'string', 'max:255'],
            'name_ar' => ['required', 'string', 'max:255'],
            'desc_en' => ['required', 'string'],
            'desc_ar' => ['required', 'string'],
            'img' => ['required', 'string', 'max:50'],
            'questions_no' => ['required', 'integer', 'min:1', 'max:127'],
            'difficulty' => ['required', 'integer', 'min:1', 'max:127'],
            'duration_mins' => ['required', 'integer', 'min:1', 'max:32767'],
            'skill_id' => ['required', 'integer', 'exists:skills,id'],
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
            'desc_en.required' => 'The English description field is required.',
            'desc_ar.required' => 'The Arabic description field is required.',
            'img.required' => 'The image field is required.',
            'img.max' => 'The image must not exceed 50 characters.',
            'questions_no.required' => 'The number of questions field is required.',
            'questions_no.integer' => 'The number of questions must be an integer.',
            'difficulty.required' => 'The difficulty field is required.',
            'difficulty.integer' => 'The difficulty must be an integer.',
            'duration_mins.required' => 'The duration field is required.',
            'duration_mins.integer' => 'The duration must be an integer.',
            'skill_id.required' => 'The skill field is required.',
            'skill_id.exists' => 'The selected skill does not exist.',
            'active.boolean' => 'The active field must be true or false.',
        ];
    }
}

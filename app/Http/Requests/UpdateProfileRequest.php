<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255'],
            'username' => ['sometimes', 'string', 'max:255', Rule::unique('users')->ignore(auth()->id())],
            'email' => ['sometimes', 'string', 'email', 'max:255', Rule::unique('users')->ignore(auth()->id())],
            'phone_number' => ['nullable', 'string', 'max:20'],
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
            'name.max' => 'The name must not exceed 255 characters.',
            'username.max' => 'The username must not exceed 255 characters.',
            'username.unique' => 'This username is already taken.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',
            'phone_number.max' => 'The phone number must not exceed 20 characters.',
        ];
    }
}

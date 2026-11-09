<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{9,15}$/'],
            'university' => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'string', 'alpha_num', 'max:30'],
            'ktm' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
        ];
    }

    /**
     * Get the human readable names for the validated attributes.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'full name',
            'email' => 'email address',
            'phone' => 'phone number',
            'student_id' => 'student ID (NIM)',
            'ktm' => 'KTM',
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone.regex' => 'The phone number must contain 9 to 15 digits and may start with +.',
            'ktm.mimes' => 'The KTM must be a PDF, JPG, or PNG file.',
            'ktm.max' => 'The KTM file may not be larger than 2 MB.',
        ];
    }
}

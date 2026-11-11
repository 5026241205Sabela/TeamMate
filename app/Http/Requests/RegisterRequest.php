<?php

namespace App\Http\Requests;

use App\Models\Account;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
     * Normalize the input before validation so email uniqueness is case-insensitive.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('email'))) {
            $this->merge(['email' => mb_strtolower(trim($this->input('email')))]);
        }
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
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(Account::class, 'email')->where(
                    fn ($query) => $query->where('verification_status', '!=', 'rejected')
                ),
            ],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{9,15}$/'],
            'university' => ['required', 'string', 'max:255'],
            'student_id' => [
                'required',
                'string',
                'alpha_num',
                'max:30',
                Rule::unique(Account::class, 'nim')->where(
                    fn ($query) => $query->where('verification_status', '!=', 'rejected')
                ),
            ],
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
            'email.unique' => 'This email address is already registered.',
            'student_id.unique' => 'This student ID (NIM) is already registered.',
        ];
    }
}

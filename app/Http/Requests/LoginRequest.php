<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize NIM or email inputs into a single "login" identifier.
     */
    protected function prepareForValidation(): void
    {
        $raw = $this->input('login');

        if (! is_string($raw) || trim($raw) === '') {
            $fallback = $this->input('nim') ?? $this->input('email');

            if (is_string($fallback)) {
                $this->merge(['login' => trim($fallback)]);
            }

            return;
        }

        $this->merge(['login' => trim($raw)]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
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
            'login' => 'NIM atau email',
        ];
    }
}

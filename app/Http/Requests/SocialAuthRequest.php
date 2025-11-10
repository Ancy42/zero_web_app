<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialAuthRequest extends FormRequest
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
            'name' => 'nullable|string|max:200',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|numeric',
            'auth_type' => 'required|string|max:100',
            'id' => 'nullable|string|max:255',
            'profile_url' => 'nullable|string',
            'gender' => 'nullable|string|max:255',
        ];
    }
}

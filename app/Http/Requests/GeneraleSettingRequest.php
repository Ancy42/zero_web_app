<?php

namespace App\Http\Requests;

use App\Rules\EmailRule;
use Illuminate\Foundation\Http\FormRequest;

class GeneraleSettingRequest extends FormRequest
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
            'name' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'email' => ['nullable', 'email', 'max:255', new EmailRule],
            'mobile' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'google_playstore_url' => 'nullable|string|max:255',
            'app_store_url' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:4',
            'currency_position' => 'nullable|string',
            'direction' => 'nullable|string',
            'favicon' => 'nullable|image|mimes:png,jpg,jpeg,png,svg,webp|max:2048',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,png,svg,webp|max:2048',
            'app_logo' => 'nullable|image|mimes:png,jpg,jpeg,png,svg,webp|max:2048',
            'footer_phone' => 'nullable|string|max:255',
            'footer_email' => ['nullable', 'email', new EmailRule],
            'footer_text' => 'nullable|string|max:255',
            'footer_description' => 'nullable|string|max:255',
            'footer_logo' => 'nullable|image|mimes:png,jpg,jpeg,png,gif,webp|max:2048',
            'footer_qrcode' => 'nullable|image|mimes:png,jpg,jpeg,png,gif,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        $request = request();
        if ($request->is('api/*')) {
            $header = strtolower($request->header('accept-language'));
            $lan = (preg_match('/^[a-z]+$/', $header)) ? $header : 'en';
            app()->setLocale($lan);
        }

        return [
            'currency.max' => __('The currency code must be a maximum of 4 characters.'),
            'favicon.image' => __('The favicon must be an image.'),
            'logo.image' => __('The logo must be an image.'),
            'favicon.mimes' => __('The favicon must be a file of type: png, jpg, jpeg, png, svg, webp.'),
            'logo.mimes' => __('The logo must be a file of type: png, jpg, jpeg, png, svg, webp.'),
        ];
    }
}

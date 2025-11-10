<?php

namespace App\Http\Requests;

use App\Models\VerifyManage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;

class AddressRequest extends FormRequest
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
        $verifyManage = Cache::rememberForever('verify_manage', function () {
            return VerifyManage::first();
        });

        $min = $verifyManage?->phone_min_length ?? 9;
        $max = $verifyManage?->phone_max_length ?? 16;

        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric|min_digits:'.$min.'|max_digits:'.$max,
            'area' => 'nullable|string|max:255',
            'flat_no' => 'nullable|string|max:255',
            'post_code' => 'nullable|string|max:255',
            'address_line' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'address_type' => 'required|string|max:255',
            'is_default' => 'nullable|boolean',
            'longitude' => 'nullable|numeric|max:255',
            'latitude' => 'nullable|numeric|max:255',
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
            'name.required' => __('The name field is required'),
            'name.max' => __('The name may not be greater than 255 characters'),
            'name.string' => __('The name must be a string'),
            'phone.required' => __('The phone field is required.'),
            'area.required' => __('The area field is required'),
            'area.max' => __('The area may not be greater than 255 characters'),
            'address_type.required' => __('The address type field is required'),
            'address_type.max' => __('The address type may not be greater than 255 characters'),
            'post_code.required' => __('The post code field is required'),
            'post_code.max' => __('The post code may not be greater than 255 characters'),
            'flat_no.max' => __('The flat no may not be greater than 255 characters'),
            'address_line.required' => __('The address line field is required'),
            'address_line.max' => __('The address line may not be greater than 255 characters'),
            'address_line2.max' => __('The address line 2 may not be greater than 255 characters'),
        ];
    }
}

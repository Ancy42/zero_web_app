<?php

namespace App\Http\Requests;

use App\Models\VerifyManage;
use App\Rules\EmailRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;

class RegistrationRequest extends FormRequest
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

        $emailRequired = 'required_if:phone,null';

        if ($verifyManage?->register_otp_type == 'email' || $verifyManage?->forgot_otp_type == 'email') {
            $emailRequired = 'required';
        }

        $phoneRequired = $verifyManage?->phone_required ? 'required' : 'nullable';
        $phoneRequired = $verifyManage ? $phoneRequired : 'required';

        $min = $verifyManage?->phone_min_length ?? 9;
        $max = $verifyManage?->phone_max_length ?? 16;

        $phoneValidate = [$phoneRequired, 'unique:users,phone', 'min_digits:'.$min, 'max_digits:'.$max];
        $countryRequired = $this->routeIs('admin.customer.store') ? 'nullable' : 'required';

        return [
            'name' => 'required|string|max:200',
            'phone' => $phoneValidate,
            'email' => [$emailRequired, 'email', new EmailRule, 'unique:users,email'],
            'password' => 'required|string|min:6',
            'country' => [$countryRequired, 'string', 'max:100'],
            'gender' => ['nullable', 'string'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
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
            'name.required' => __('The name field is required.'),
            'phone.required' => __('The phone field is required.'),
            'phone.unique' => __('The phone has already been taken.'),
            'password.required' => __('The password field is required.'),
            'password.min' => __('The password must be at least 6 characters.'),
            'email.required' => __('The email field is required.'),
            'email.email' => __('The email must be a valid email address.'),
            'email.unique' => __('The email has already been taken.'),
        ];
    }
}

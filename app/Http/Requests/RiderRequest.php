<?php

namespace App\Http\Requests;

use App\Models\VerifyManage;
use App\Rules\EmailRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Cache;

class RiderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $verifyManage = Cache::rememberForever('verify_manage', function () {
            return VerifyManage::first();
        });

        $required = 'required';
        $userId = null;
        if ($this->routeIs('rider.profile.update', 'admin.rider.update')) {
            $required = 'nullable';
            $userId = $this->routeIs('admin.rider.update') ? $this->user->id : auth()->user()->id;
        }

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

        $phoneValidate = [$phoneRequired, 'min_digits:'.$min, 'max_digits:'.$max, 'unique:users,phone,'.$userId];

        return [
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'phone' => $phoneValidate,
            'email' => [$emailRequired, new EmailRule, 'unique:users,email,'.$userId],
            'password' => "$required|min:6|confirmed",
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
            'gender' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'driving_lience' => 'nullable|string',
            'vehicle_type' => 'required|string',
        ];
    }

    public function messages()
    {
        $request = request();
        if ($request->is('api/*')) {
            $header = strtolower($request->header('accept-language'));
            $lan = (preg_match('/^[a-z]+$/', $header)) ? $header : 'en';
            app()->setLocale($lan);
        }

        return [
            'first_name.required' => __('The first name field is required.'),
            'phone.required' => __('The phone field is required.'),
            'phone.unique' => __('The phone has already been taken.'),
            'email.unique' => __('The email has already been taken.'),
            'password.min' => __('The password must be at least 6 characters.'),
            'password.required' => __('The password field is required.'),
            'password.confirmed' => __('The password and confirmation password do not match.'),
            'vehicle_type.required' => __('The vehicle type field is required.'),
            'profile_photo.image' => __('The profile photo must be an image.'),
            'profile_photo.mimes' => __('The profile photo must be a file of type: jpg, jpeg, png, svg.'),
            'profile_photo.max' => __('The profile photo may not be greater than 2MB.'),
        ];
    }
}

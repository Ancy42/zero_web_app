<?php

namespace Modules\Purchase\App\Http\Requests;

use App\Rules\EmailRule;
use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $user = null;

        if ($this->routeIs('shop.supplier.update')) {
            $user = $this->supplier?->user;
        }

        return [
            'name' => ['required', 'string', 'max:200'],
            'phone' => ['required', 'numeric', 'unique:users,phone,' . $user?->id],
            'email' => ['required', 'email', 'unique:users,email,' . $user?->id, new EmailRule],
            'profile_photo' => ['nullable', 'image', 'max:2048', 'mimes:png,jpg,jpeg,gif,svg,webp'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}

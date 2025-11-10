<?php

namespace App\Http\Requests;

use App\Enums\SubscriptionStatus;
use App\Repositories\ShopRepository;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'shop_ids' => 'required|array',
            'shop_ids.*' => 'required|exists:shops,id',
            'address_id' => 'required|exists:addresses,id',
            'note' => 'nullable|string',
            'payment_method' => 'required|string',
            'coupon_code' => 'nullable|string|max:50',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $generalSetting = generaleSetting('setting');

            if ($generalSetting?->business_based_on != 'subscription') {
                return;
            }

            $shops = ShopRepository::query()->whereIn('id', $this->shop_ids)->get();

            foreach ($shops as $shop) {
                if ($shop->user->hasRole('root')) {
                    continue;
                }

                $subscription = $shop->currentSubscription;

                if (! $subscription) {
                    $validator->errors()->add('shop_ids', 'Some of the selected shops are not available.');
                    return;
                }
            }
        });
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
            'shop_ids.required' => __('The shop field is required.'),
            'shop_ids.array' => __('The shop ids must be an array.'),
            'shop_ids.*.required' => __('The shop field is required.'),
            'shop_ids.*.exists' => __('The selected shop id is invalid.'),
            'address_id.required' => __('The address field is required.'),
            'address_id.exists' => __('The selected address id is invalid.'),
            'payment_method.required' => __('The payment method field is required.'),
        ];
    }
}

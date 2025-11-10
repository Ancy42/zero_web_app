<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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

        $additionThumbnail = $this->product?->medias?->isNotEmpty() ? 'nullable' : 'nullable';
        $thumbnail = $this->product?->media ? 'nullable' : 'required';

        if ($this->is('api/*')) {
            $additionThumbnail = 'nullable';
        }

        return [
            'name' => 'required|string|max:191',
            'description' => 'required|string',
            'slug' => 'nullable|string|max:191|unique:products,slug,'.$this->product?->id,
            'short_description' => 'required|string|max:191',
            'categories' => 'required|array',
            'categories.*' => 'required|exists:categories,id',
            'brand' => 'nullable|exists:brands,id',
            'code' => 'required|numeric|digits_between:5,25',
            'unit' => 'required|string|max:200',
            'buy_price' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|max:'.$this->price,
            'quantity' => 'required|integer|min:0',
            'min_order_quantity' => 'nullable|integer|min:0',
            'meta_title' => 'nullable|string|max:191',
            'meta_description' => 'nullable|string|max:200',
            'thumbnail' => "$thumbnail|max:255",
            'additionThumbnail' => "$additionThumbnail|array",
            'additionThumbnail.*' => 'max:255',

            'previousThumbnail' => 'nullable|array',
            'previousThumbnail.*.id' => 'nullable|exists:media,id',
            'previousThumbnail.*.file' => 'nullable|file|mimes:png,jpg,jpeg,webp|max:2048',
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
            'name.max' => __('The name may not be greater than 191 characters.'),
            'description.required' => __('The description field is required.'),
            'short_description.required' => __('The short description field is required.'),
            'short_description.max' => __('The short description may not be greater than 191 characters.'),
            'category.required' => __('The category field is required.'),
            'category.exists' => __('The selected category is invalid.'),
            'code.required' => __('The code field is required.'),
            'code.unique' => __('The code has already been taken.'),
            'code.numeric' => __('The code must be a number.'),
            'code.digits_between' => __('The code must be 5-7 digits.'),
            'price.required' => __('The price field is required.'),
            'price.numeric' => __('The price must be a number.'),
            'price.min' => __('The price must be at least 0.'),
            'discount_price.numeric' => __('The discount price must be a number.'),
            'discount_price.min' => __('The discount price must be at least 0.'),
            'discount_price.max' => __('The discount price must be less than price.'),
            'quantity.required' => __('The quantity field is required.'),
            'quantity.integer' => __('The quantity must be an integer.'),
            'quantity.min' => __('The quantity must be at least 0.'),
            'min_order_quantity.required' => __('The min order quantity field is required.'),
            'min_order_quantity.integer' => __('The min order quantity must be an integer.'),
            'min_order_quantity.min' => __('The min order quantity must be at least 0.'),
            'thumbnail.required' => __('The thumbnail field is required.'),
            'thumbnail.image' => __('The thumbnail must be an image.'),
            'thumbnail.mimes' => __('The thumbnail must be a file of type: png, jpg, jpeg, webp.'),
            'thumbnail.max' => __('The thumbnail may not be greater than 2048 kilobytes.'),
            'additionThumbnail.required' => __('The addition thumbnail field is required.'),
            'additionThumbnail.image' => __('The addition thumbnail must be an image.'),
            'additionThumbnail.mimes' => __('The addition thumbnail must be a file of type: png, jpg, jpeg, webp.'),
            'additionThumbnail.max' => __('The addition thumbnail may not be greater than 2048 kilobytes.'),
        ];
    }
}

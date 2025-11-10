<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FlashSaleRequest extends FormRequest
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
        $file = $this->routeIs('admin.flashSale.update') ? 'nullable' : 'required';

        return [
            'name' => 'required|string|max:100',
            'discount' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'thumbnail' => "$file|max:255",
            'description' => 'nullable|string|max:191',
        ];
    }
}

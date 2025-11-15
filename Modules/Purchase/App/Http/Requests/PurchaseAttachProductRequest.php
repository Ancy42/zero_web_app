<?php

namespace Modules\Purchase\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseAttachProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'buying_price' => 'required|numeric',
            'product_barcodes' => 'nullable|array',
            'product_barcodes.*' => 'nullable|unique:product_skus,sku',

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

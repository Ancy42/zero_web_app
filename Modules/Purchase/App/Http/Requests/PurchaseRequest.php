<?php

namespace Modules\Purchase\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
            'receive_date' => 'required|date',
            'note' => 'nullable|string',
            'slip_image' => 'nullable|file|mimes:png,jpg,jpeg,webp,svg,pdf|max:2048',
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

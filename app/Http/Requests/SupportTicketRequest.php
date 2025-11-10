<?php

namespace App\Http\Requests;

use App\Rules\EmailRule;
use Illuminate\Foundation\Http\FormRequest;

class SupportTicketRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'issue_type' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'email' => ['required', 'string', 'max:255', new EmailRule],
            'phone' => 'nullable|numeric|digits_between:8,16',
            'order_number' => 'nullable|string|max:200',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,pdf',
        ];
    }
}

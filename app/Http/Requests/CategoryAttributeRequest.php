<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryAttributeRequest extends FormRequest
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
        $categoryAttribute = $this->route('categoryAttribute');
        $currentId = $categoryAttribute?->id;

        $categoryID = request()->input('category_id');
        $parentID   = request()->input('parent_id');

        $baseRule = Rule::unique('category_attributes', 'name')->ignore($currentId)
            ->where(function ($query) use ($categoryID, $parentID) {
                if ($categoryID) {
                    $query->where('category_id', $categoryID);
                } else {
                    $query->whereNull('category_id');
                }

                if ($parentID) {
                    $query->where('parent_id', $parentID);
                } else {
                    $query->whereNull('parent_id');
                }
            });
        return [
            'name' => ['required', 'string', 'max:255', $baseRule],
        ];
    }
}

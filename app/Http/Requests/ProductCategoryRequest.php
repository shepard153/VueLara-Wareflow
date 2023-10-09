<?php

namespace App\Http\Requests;

use App\Rules\ProductCategoryLevelsLimit;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'min:4', 'unique:product_categories,name,' . $this?->id ?? null],
            'parent_id' => ['sometimes', 'required_if:is_subcategory,true', new ProductCategoryLevelsLimit()]
        ];
    }
}

<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlantRequest extends FormRequest
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
            'page' => 'sometimes|integer|min:1',
            'search' => 'sometimes|string',

            'filters' => 'array',
            'filters.*.field' => 'required_with:filters|string',

            'filters.*.text' => 'nullable|array',
            'filters.*.text.contains' => 'required_with:filters.*.text|string',

            'filters.*.number' => 'nullable|array',
            'filters.*.number.equals' => 'sometimes|numeric',
            'filters.*.number.greater_than' => 'sometimes|numeric',
            'filters.*.number.greater_than_or_equal_to' => 'sometimes|numeric',
            'filters.*.number.less_than' => 'sometimes|numeric',
            'filters.*.number.less_than_or_equal_to' => 'sometimes|numeric',

            'filters.*.select' => 'nullable|array',
            'filters.*.select.equals' => 'required_with:filters.*.select|string',

            'sorts' => 'array',
            'sorts.*.field' => ['required_with:sorts', Rule::in(['name', 'common_name'])],
            'sorts.*.direction' => ['required_with:sorts.*.field', Rule::in(['asc', 'desc'])],
        ];
    }
}

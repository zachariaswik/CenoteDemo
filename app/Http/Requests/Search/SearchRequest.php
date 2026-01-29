<?php

namespace App\Http\Requests\Search;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'query' => ['required', 'string', 'min:2', 'max:200'],
            'page' => ['sometimes', 'integer', 'min:1'],
        ];
    }
}

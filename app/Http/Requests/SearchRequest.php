<?php

namespace App\Http\Requests;

use App\Data\SearchData;
use App\Enums\RequestOrderBy;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'order_by' => ['sometimes', 'required', new Enum(RequestOrderBy::class)],
            'per_page' => ['sometimes', 'integer', 'min:1'],
            'page' => ['sometimes', 'integer', 'min:1'],
            'search' => ['sometimes', 'string'],
        ];
    }

    public function __invoke(): SearchData
    {
        return SearchData::from($this->safe());
    }
}

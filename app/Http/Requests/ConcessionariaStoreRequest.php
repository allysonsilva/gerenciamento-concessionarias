<?php

namespace App\Http\Requests;

use App\Models\Concessionaria;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ConcessionariaStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->filled('cnpj')) {
            $this->merge(['cnpj' => sanitize_cnpj($this->input('cnpj'))]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'symbol' => ['required', 'string', Rule::unique(Concessionaria::class)->ignore($this->route('concessionaria'))],
            'cnpj' => ['required', 'string', 'digits:14', Rule::unique(Concessionaria::class)->ignore($this->route('concessionaria'))],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'symbol' => 'sigla',
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Models\Concessionaria;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ConcessionariaStoreRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->filled('cnpj')) {
            $cnpjOnlyNumbers = filter_var(str_replace(array('.','-','/'), '', trim($this->input('cnpj'))), FILTER_SANITIZE_NUMBER_INT);

            $this->merge(['cnpj' => $cnpjOnlyNumbers]);
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

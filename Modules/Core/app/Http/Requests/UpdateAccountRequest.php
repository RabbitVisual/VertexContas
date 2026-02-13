<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by policy
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:checking,savings,cash'],
            'balance' => ['required', 'numeric'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome da conta é obrigatório.',
            'name.max' => 'O nome da conta não pode ter mais de 255 caracteres.',
            'type.required' => 'O tipo da conta é obrigatório.',
            'type.in' => 'O tipo da conta deve ser: Conta Corrente, Poupança ou Dinheiro.',
            'balance.required' => 'O saldo é obrigatório.',
            'balance.numeric' => 'O saldo deve ser um número válido.',
        ];
    }
}

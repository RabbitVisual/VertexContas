<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_id' => ['required', 'exists:accounts,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:pending,completed'],
        ];
    }

    public function messages(): array
    {
        return [
            'account_id.required' => 'Selecione uma conta.',
            'account_id.exists' => 'Conta inválida.',
            'category_id.required' => 'Selecione uma categoria.',
            'category_id.exists' => 'Categoria inválida.',
            'type.required' => 'Selecione o tipo (Receita ou Despesa).',
            'type.in' => 'Tipo inválido.',
            'amount.required' => 'Informe o valor.',
            'amount.numeric' => 'O valor deve ser numérico.',
            'amount.min' => 'O valor deve ser maior que zero.',
            'date.required' => 'Informe a data.',
            'date.date' => 'Data inválida.',
            'description.max' => 'A descrição não pode ter mais de 500 caracteres.',
            'status.required' => 'Informe o status.',
            'status.in' => 'Status inválido.',
        ];
    }
}

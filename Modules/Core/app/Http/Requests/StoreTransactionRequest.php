<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()?->id;

        return [
            'account_id' => ['required', Rule::exists('accounts', 'id')->where('user_id', $userId)],
            'category_id' => ['required', Rule::exists('categories', 'id')->where(fn ($q) => $q->whereNull('user_id')->orWhere('user_id', $userId))],
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

<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()?->id;

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'target_amount' => ['required', 'numeric', 'min:0.01'],
            'current_amount' => ['nullable', 'numeric', 'min:0'],
            'deadline' => ['nullable', 'date'],
            'monthly_contribution' => ['nullable', 'numeric', 'min:0'],
            'contribution_account_id' => [
                'nullable',
                Rule::exists('accounts', 'id')->where('user_id', $userId),
            ],
            'contribution_category_id' => [
                'nullable',
                Rule::exists('categories', 'id')->where(fn ($q) => $q->whereNull('user_id')->orWhere('user_id', $userId)),
            ],
            'contribution_recurrence_day' => ['nullable', 'integer', 'min:1', 'max:31'],
        ];

        if ((float) ($this->input('monthly_contribution') ?? 0) > 0) {
            $rules['contribution_account_id'][] = 'required';
            $rules['contribution_category_id'][] = 'required';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Informe o nome da meta.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'target_amount.required' => 'Informe o valor da meta.',
            'target_amount.numeric' => 'O valor deve ser numérico.',
            'target_amount.min' => 'O valor deve ser maior que zero.',
            'current_amount.numeric' => 'O valor atual deve ser numérico.',
            'current_amount.min' => 'O valor atual não pode ser negativo.',
            'deadline.date' => 'Data inválida.',
            'monthly_contribution.numeric' => 'O valor da contribuição deve ser numérico.',
            'monthly_contribution.min' => 'A contribuição não pode ser negativa.',
            'contribution_account_id.required' => 'Selecione a conta para a contribuição automática.',
            'contribution_category_id.required' => 'Selecione a categoria para a contribuição automática.',
            'contribution_recurrence_day.min' => 'O dia deve ser entre 1 e 31.',
            'contribution_recurrence_day.max' => 'O dia deve ser entre 1 e 31.',
        ];
    }
}

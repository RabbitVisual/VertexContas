<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'target_amount' => ['required', 'numeric', 'min:0.01'],
            'current_amount' => ['nullable', 'numeric', 'min:0'],
            'deadline' => ['nullable', 'date', 'after:today'],
        ];
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
            'deadline.after' => 'O prazo deve ser uma data futura.',
        ];
    }
}

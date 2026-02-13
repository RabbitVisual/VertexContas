<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:income,expense'],
            'icon' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Informe o nome da categoria.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'type.required' => 'Selecione o tipo (Receita ou Despesa).',
            'type.in' => 'Tipo inválido.',
            'icon.max' => 'O ícone não pode ter mais de 50 caracteres.',
            'color.regex' => 'Cor inválida. Use o formato hexadecimal (#RRGGBB).',
        ];
    }
}

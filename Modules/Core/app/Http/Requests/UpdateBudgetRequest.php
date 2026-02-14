<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBudgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'limit_amount' => 'required|numeric|min:0',
            'period' => 'required|in:monthly,yearly',
            'alert_threshold' => 'nullable|integer|min:50|max:100',
            'allow_exceed' => 'nullable|boolean',
        ];
    }
}

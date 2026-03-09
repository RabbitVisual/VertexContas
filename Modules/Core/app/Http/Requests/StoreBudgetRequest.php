<?php

namespace Modules\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBudgetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()?->id;

        return [
            'category_id' => 'required|exists:categories,id',
            'account_id' => [
                'nullable',
                Rule::exists('accounts', 'id')->where('user_id', $userId),
            ],
            'limit_amount' => 'required|numeric|min:0',
            'period' => 'required|in:monthly,yearly',
            'is_recurring' => 'nullable|boolean',
            'period_start' => ['nullable', 'date', 'required_if:is_recurring,false,0'],
            'alert_threshold' => 'nullable|integer|min:50|max:100',
            'allow_exceed' => 'nullable|boolean',
        ];
    }
}

<?php

declare(strict_types=1);

namespace Modules\PWA\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePwaVersionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'version' => ['required', 'string', 'max:32', 'unique:pwa_versions,version'],
            'release_notes' => ['nullable', 'string', 'max:2000'],
            'is_force_update' => ['boolean'],
            'released_at' => ['nullable', 'date'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->has('is_force_update')) {
            $this->merge(['is_force_update' => false]);
        }
    }
}

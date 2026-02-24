<?php

declare(strict_types=1);

namespace Modules\PWA\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecordPwaInstallRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'app_version' => ['required', 'string', 'max:32'],
            'device_fingerprint' => ['required', 'string', 'max:64'],
            'platform' => ['nullable', 'string', 'in:web,android,ios'],
        ];
    }
}

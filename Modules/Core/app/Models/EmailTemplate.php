<?php

declare(strict_types=1);

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $table = 'email_templates';

    protected $fillable = [
        'key',
        'subject',
        'content_html',
        'variables_hint',
        'is_html',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'is_html' => 'boolean',
        ];
    }
}

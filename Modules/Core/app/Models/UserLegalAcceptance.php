<?php

declare(strict_types=1);

namespace Modules\Core\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserLegalAcceptance extends Model
{
    protected $fillable = [
        'user_id',
        'legal_document_id',
        'version',
        'accepted_at',
        'ip_address',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function legalDocument()
    {
        return $this->belongsTo(LegalDocument::class);
    }
}

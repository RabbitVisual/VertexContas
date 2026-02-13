<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportAuditLog extends Model
{
    protected $fillable = [
        'agent_id',
        'user_id',
        'action',
        'metadata',
        'ip_address',
    ];

    protected $casts = [
        'metadata' => 'json',
    ];

    public $timestamps = false;

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

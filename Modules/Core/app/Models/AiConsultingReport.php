<?php

declare(strict_types=1);

namespace Modules\Core\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiConsultingReport extends Model
{
    protected $table = 'ai_consulting_reports';

    protected $fillable = [
        'user_id',
        'period',
        'ai_conclusion',
        'ai_projection',
        'snapshot',
    ];

    protected $casts = [
        'snapshot' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function findForUserAndPeriod(int $userId, string $period): ?self
    {
        return self::where('user_id', $userId)
            ->where('period', $period)
            ->first();
    }
}

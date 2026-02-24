<?php

declare(strict_types=1);

namespace Modules\Core\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Models\Goal;

class GoalCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Goal $goal
    ) {}
}

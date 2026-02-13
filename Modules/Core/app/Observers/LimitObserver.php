<?php

namespace Modules\Core\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Services\SubscriptionLimitService;

class LimitObserver
{
    protected $limitService;

    public function __construct(SubscriptionLimitService $limitService)
    {
        $this->limitService = $limitService;
    }

    /**
     * Handle the Model "created" event.
     */
    public function created(Model $model): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        // Determine entity based on model class
        $entity = $this->getEntityName($model);

        if ($entity) {
            $this->limitService->checkAndNotify($user, $entity);
        }
    }

    protected function getEntityName(Model $model): ?string
    {
        $class = get_class($model);

        if ($class === \Modules\Core\Models\Account::class) return 'account';
        if ($class === \Modules\Core\Models\Goal::class) return 'goal';
        if ($class === \Modules\Core\Models\Budget::class) return 'budget';

        if ($class === \Modules\Core\Models\Transaction::class) {
            // Assuming 'type' is 'income' or 'expense'
            return $model->type === 'income' ? 'income' : 'expense';
        }

        return null;
    }
}

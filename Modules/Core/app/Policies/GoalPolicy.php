<?php

namespace Modules\Core\Policies;

use App\Models\User;
use Modules\Core\Models\Goal;
use Modules\Core\Services\SubscriptionLimitService;

class GoalPolicy
{
    protected SubscriptionLimitService $limitService;

    public function __construct(SubscriptionLimitService $limitService)
    {
        $this->limitService = $limitService;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('core.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Goal $goal): bool
    {
        return $user->id === $goal->user_id && $user->can('core.view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('core.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Goal $goal): bool
    {
        return $user->id === $goal->user_id && $user->can('core.create');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Goal $goal): bool
    {
        return $user->id === $goal->user_id && $user->can('core.create');
    }
}

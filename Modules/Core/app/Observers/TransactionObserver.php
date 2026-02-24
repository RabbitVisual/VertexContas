<?php

declare(strict_types=1);

namespace Modules\Core\Observers;

use Modules\Core\Events\GoalCompleted;
use Modules\Core\Models\Goal;
use Modules\Core\Models\RecurringTransaction;
use Modules\Core\Models\Transaction;

class TransactionObserver
{
    public function created(Transaction $transaction): void
    {
        if ($transaction->status === 'completed') {
            $this->updateAccountBalance($transaction, 'add');
        }

        $this->syncGoalOnCreated($transaction);
    }

    public function updated(Transaction $transaction): void
    {
        if ($transaction->isDirty(['amount', 'type', 'status', 'account_id'])) {
            $original = $transaction->getOriginal();
            if (($original['status'] ?? null) === 'completed') {
                $this->reverseTransaction($original);
            }
            if ($transaction->status === 'completed') {
                $this->updateAccountBalance($transaction, 'add');
            }
        }

        if ($transaction->isDirty(['goal_id', 'amount', 'type', 'status'])) {
            $this->syncGoalOnUpdated($transaction);
        }
    }

    public function deleted(Transaction $transaction): void
    {
        if ($transaction->status === 'completed') {
            $this->updateAccountBalance($transaction, 'subtract');
        }

        $this->syncGoalOnDeleted($transaction);
    }

    protected function updateAccountBalance(Transaction $transaction, string $operation): void
    {
        $account = $transaction->account;
        if (! $account) {
            return;
        }

        $amount = $transaction->amount;
        if ($operation === 'add') {
            if ($transaction->type === 'income') {
                $account->increment('balance', $amount);
            } else {
                $account->decrement('balance', $amount);
            }
        } else {
            if ($transaction->type === 'income') {
                $account->decrement('balance', $amount);
            } else {
                $account->increment('balance', $amount);
            }
        }
    }

    protected function reverseTransaction(array $original): void
    {
        $account = \Modules\Core\Models\Account::find($original['account_id'] ?? null);
        if (! $account) {
            return;
        }

        $amount = $original['amount'];
        if (($original['type'] ?? '') === 'income') {
            $account->decrement('balance', $amount);
        } else {
            $account->increment('balance', $amount);
        }
    }

    protected function syncGoalOnCreated(Transaction $transaction): void
    {
        if ($transaction->goal_id === null || $transaction->type !== 'expense' || $transaction->status !== 'completed') {
            return;
        }

        $this->incrementGoalBy($transaction->goal_id, (float) $transaction->amount);
    }

    protected function syncGoalOnUpdated(Transaction $transaction): void
    {
        $original = $transaction->getOriginal();
        $oldGoalId = $original['goal_id'] ?? null;
        $newGoalId = $transaction->goal_id;
        $oldAmount = (float) ($original['amount'] ?? 0);
        $newAmount = (float) $transaction->amount;
        $oldCompleted = ($original['status'] ?? '') === 'completed' && ($original['type'] ?? '') === 'expense';
        $newCompleted = $transaction->status === 'completed' && $transaction->type === 'expense';

        if ($oldGoalId && $oldCompleted) {
            $this->decrementGoalBy((int) $oldGoalId, $oldAmount);
        }

        if ($newGoalId && $newCompleted) {
            $this->incrementGoalBy((int) $newGoalId, $newAmount);
        }
    }

    protected function syncGoalOnDeleted(Transaction $transaction): void
    {
        if ($transaction->goal_id === null || $transaction->type !== 'expense') {
            return;
        }

        $this->decrementGoalBy((int) $transaction->goal_id, (float) $transaction->amount);
    }

    protected function incrementGoalBy(int $goalId, float $amount): void
    {
        $goal = Goal::find($goalId);
        if (! $goal || $amount <= 0) {
            return;
        }

        $target = (float) $goal->target_amount;
        $current = (float) $goal->current_amount;
        $newCurrent = min($current + $amount, $target);
        $goal->current_amount = $newCurrent;

        $wasNotCompleted = $goal->completed_at === null;
        if ($newCurrent >= $target) {
            $goal->completed_at = $goal->completed_at ?? now();
            RecurringTransaction::where('goal_id', $goalId)->update(['is_active' => false]);
        }

        $goal->save();

        if ($wasNotCompleted && $goal->completed_at !== null) {
            event(new GoalCompleted($goal));
        }
    }

    protected function decrementGoalBy(int $goalId, float $amount): void
    {
        $goal = Goal::find($goalId);
        if (! $goal || $amount <= 0) {
            return;
        }

        $current = (float) $goal->current_amount;
        $goal->current_amount = max(0, $current - $amount);
        if ($goal->completed_at !== null) {
            $goal->completed_at = null;
        }
        $goal->save();
    }
}

<?php

namespace Modules\Core\Observers;

use Modules\Core\Models\Transaction;

class TransactionObserver
{
    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        if ($transaction->status === 'completed') {
            $this->updateAccountBalance($transaction, 'add');
        }
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        // Only process if status changed to/from completed or amount changed
        if ($transaction->isDirty(['amount', 'type', 'status', 'account_id'])) {
            $original = $transaction->getOriginal();

            // Reverse the original transaction effect
            if ($original['status'] === 'completed') {
                $this->reverseTransaction($original);
            }

            // Apply the new transaction effect
            if ($transaction->status === 'completed') {
                $this->updateAccountBalance($transaction, 'add');
            }
        }
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        if ($transaction->status === 'completed') {
            $this->updateAccountBalance($transaction, 'subtract');
        }
    }

    /**
     * Update account balance based on transaction.
     */
    protected function updateAccountBalance(Transaction $transaction, string $operation): void
    {
        $account = $transaction->account;

        if (!$account) {
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
            // Reverse operation
            if ($transaction->type === 'income') {
                $account->decrement('balance', $amount);
            } else {
                $account->increment('balance', $amount);
            }
        }
    }

    /**
     * Reverse a transaction effect using original values.
     */
    protected function reverseTransaction(array $original): void
    {
        $account = \Modules\Core\Models\Account::find($original['account_id']);

        if (!$account) {
            return;
        }

        $amount = $original['amount'];

        if ($original['type'] === 'income') {
            $account->decrement('balance', $amount);
        } else {
            $account->increment('balance', $amount);
        }
    }
}

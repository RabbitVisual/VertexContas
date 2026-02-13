<?php

namespace Modules\Core\Services;

use Illuminate\Support\Facades\DB;
use Modules\Core\Models\Account;
use Modules\Core\Models\Transaction;

class TransferService
{
    /**
     * Transfer money between two accounts atomically.
     *
     * @throws \Exception
     */
    public function transfer(
        Account $fromAccount,
        Account $toAccount,
        float $amount,
        string $description = '',
        int $categoryId = null
    ): array {
        // Validate amount
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Transfer amount must be greater than zero.');
        }

        // Check if source account has sufficient balance
        if ($fromAccount->balance < $amount) {
            throw new \Exception('Insufficient balance in source account.');
        }

        // Prevent transfer to same account
        if ($fromAccount->id === $toAccount->id) {
            throw new \InvalidArgumentException('Cannot transfer to the same account.');
        }

        // Perform atomic transfer
        return DB::transaction(function () use ($fromAccount, $toAccount, $amount, $description, $categoryId) {
            // Create expense transaction (withdrawal from source account)
            $expenseTransaction = Transaction::create([
                'user_id' => $fromAccount->user_id,
                'account_id' => $fromAccount->id,
                'destination_account_id' => $toAccount->id,
                'category_id' => $categoryId,
                'type' => 'expense',
                'amount' => $amount,
                'date' => now(),
                'description' => $description ?: "Transferência para {$toAccount->name}",
                'status' => 'completed',
            ]);

            // Create income transaction (deposit to destination account)
            $incomeTransaction = Transaction::create([
                'user_id' => $toAccount->user_id,
                'account_id' => $toAccount->id,
                'destination_account_id' => $fromAccount->id,
                'category_id' => $categoryId,
                'type' => 'income',
                'amount' => $amount,
                'date' => now(),
                'description' => $description ?: "Transferência de {$fromAccount->name}",
                'status' => 'completed',
                'parent_id' => $expenseTransaction->id,
            ]);

            // Update parent_id on expense transaction to link both
            $expenseTransaction->update(['parent_id' => $incomeTransaction->id]);

            return [
                'expense' => $expenseTransaction->fresh(),
                'income' => $incomeTransaction->fresh(),
            ];
        });
    }

    /**
     * Check if a transaction is part of a transfer.
     */
    public function isTransfer(Transaction $transaction): bool
    {
        return $transaction->destination_account_id !== null || $transaction->parent_id !== null;
    }

    /**
     * Get the linked transaction for a transfer.
     */
    public function getLinkedTransaction(Transaction $transaction): ?Transaction
    {
        if (!$this->isTransfer($transaction)) {
            return null;
        }

        return Transaction::where('parent_id', $transaction->id)
            ->orWhere('id', $transaction->parent_id)
            ->first();
    }
}

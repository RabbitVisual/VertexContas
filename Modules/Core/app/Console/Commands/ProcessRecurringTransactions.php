<?php

namespace Modules\Core\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Models\RecurringTransaction;

class ProcessRecurringTransactions extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'core:run-recurring';

    /**
     * The console command description.
     */
    protected $description = 'Process all due recurring transactions (only scheduled "Repetir", not baseline planning)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Processing recurring transactions...');

        $query = RecurringTransaction::active()
            ->due()
            ->processable()
            ->with(['user', 'account', 'category']);

        if (Schema::hasColumn('recurring_transactions', 'is_baseline')) {
            $query->where('is_baseline', false);
        }

        $recurringTransactions = $query->get();

        if ($recurringTransactions->isEmpty()) {
            $this->info('No recurring transactions to process.');
            return self::SUCCESS;
        }

        $processed = 0;
        $failed = 0;

        foreach ($recurringTransactions as $recurring) {
            if (! $recurring->account_id || ! $recurring->category_id) {
                continue;
            }
            try {
                $transaction = $recurring->process();

                $this->line(sprintf(
                    'Processed: %s - R$ %s for user %s',
                    $recurring->description,
                    number_format($recurring->amount, 2, ',', '.'),
                    $recurring->user->name
                ));

                $processed++;
            } catch (\Exception $e) {
                $this->error(sprintf(
                    'Failed to process recurring transaction ID %d: %s',
                    $recurring->id,
                    $e->getMessage()
                ));

                $failed++;
            }
        }

        $this->newLine();
        $this->info(sprintf(
            'Completed! Processed: %d, Failed: %d',
            $processed,
            $failed
        ));

        return self::SUCCESS;
    }
}

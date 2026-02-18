<?php

declare(strict_types=1);

namespace Modules\Notifications\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PruneNotifications extends Command
{
    protected $signature = 'notifications:prune';

    protected $description = 'Remove read notifications older than retention period (configurable in Admin)';

    public function handle(): int
    {
        if (! \Illuminate\Support\Facades\Schema::hasTable('settings')) {
            $this->warn('Settings table not found. Skipping prune.');

            return self::SUCCESS;
        }

        $autoClean = (bool) (setting('notifications_auto_clean_read') ?? true);
        if (! $autoClean) {
            $this->info('Auto-clean for read notifications is disabled.');

            return self::SUCCESS;
        }

        $retentionDays = (int) (setting('notifications_retention_days') ?? 90);
        $retentionDays = max(1, min($retentionDays, 365));

        $cutoff = now()->subDays($retentionDays);

        $deleted = DB::table('notifications')
            ->whereNotNull('read_at')
            ->where('read_at', '<', $cutoff)
            ->delete();

        if ($deleted > 0) {
            $this->info("Pruned {$deleted} read notification(s) older than {$retentionDays} days.");
        } else {
            $this->info('No notifications to prune.');
        }

        return self::SUCCESS;
    }
}

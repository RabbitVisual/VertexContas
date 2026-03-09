<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Apply recommended Free plan limits (configurable later in Admin).
     * Free: unlimited transactions (income/expense), 3 accounts, 1 goal, 1 budget.
     */
    public function up(): void
    {
        $updated = DB::table('plans')
            ->where('is_free', true)
            ->update([
                'limit_income' => -1,
                'limit_expense' => -1,
                'limit_account' => 3,
                'limit_goal' => 1,
                'limit_budget' => 1,
                'updated_at' => now(),
            ]);

        if ($updated > 0) {
            // Optional: log or no-op. Admin can change these values anytime.
        }
    }

    public function down(): void
    {
        // Restore previous common defaults (optional; admin may have changed)
        DB::table('plans')
            ->where('is_free', true)
            ->update([
                'limit_income' => 15,
                'limit_expense' => 15,
                'limit_account' => 1,
                'limit_goal' => 1,
                'limit_budget' => 1,
                'updated_at' => now(),
            ]);
    }
};

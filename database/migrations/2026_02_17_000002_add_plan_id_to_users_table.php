<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('plan_id')->nullable()->after('membership')->constrained('plans')->nullOnDelete();
        });

        $freePlanId = DB::table('plans')->where('slug', 'free')->value('id');
        $proPlanId = DB::table('plans')->where('slug', 'pro')->value('id');
        if (! $freePlanId || ! $proPlanId) {
            return;
        }

        DB::table('users')->update(['plan_id' => $freePlanId]);

        $proUserIds = DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->whereIn('roles.name', ['pro_user', 'admin'])
            ->where('model_has_roles.model_type', '=', 'App\\Models\\User')
            ->pluck('model_has_roles.model_id');

        if ($proUserIds->isNotEmpty()) {
            DB::table('users')->whereIn('id', $proUserIds)->update(['plan_id' => $proPlanId]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
        });
    }
};

<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CorePermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Core module permissions
        $coreView = Permission::findOrCreate('core.view', 'web');
        $coreCreate = Permission::findOrCreate('core.create', 'web');
        $coreReportsFull = Permission::findOrCreate('core.reports.full', 'web');

        // Note: Role assignment is handled in RolesAndPermissionsSeeder
    }
}

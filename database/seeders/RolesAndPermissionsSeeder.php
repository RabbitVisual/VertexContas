<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        // Gateways Module permissions
        $gatewaysView = Permission::findOrCreate('gateways.view', 'web');
        $gatewaysCreate = Permission::findOrCreate('gateways.create', 'web');
        $gatewaysEdit = Permission::findOrCreate('gateways.edit', 'web');
        $gatewaysDelete = Permission::findOrCreate('gateways.delete', 'web');

        // Homepage/General permissions
        $dashboardView = Permission::findOrCreate('dashboard.view', 'web');

        // Core Module permissions
        $coreView = Permission::findOrCreate('core.view', 'web');
        $coreCreate = Permission::findOrCreate('core.create', 'web');
        $coreReportsFull = Permission::findOrCreate('core.reports.full', 'web');

        // create roles and assign created permissions

        // free_user
        $role = Role::findOrCreate('free_user', 'web');
        $role->givePermissionTo($dashboardView);
        $role->givePermissionTo($gatewaysView);
        $role->givePermissionTo($coreView);
        $role->givePermissionTo($coreCreate);

        // pro_user
        $role = Role::findOrCreate('pro_user', 'web');
        $role->givePermissionTo($dashboardView);
        $role->givePermissionTo($gatewaysView);
        $role->givePermissionTo($gatewaysCreate);
        $role->givePermissionTo($gatewaysEdit);
        $role->givePermissionTo($gatewaysDelete);
        $role->givePermissionTo($coreView);
        $role->givePermissionTo($coreCreate);
        $role->givePermissionTo($coreReportsFull);

        // support
        $role = Role::findOrCreate('support', 'web');
        $role->givePermissionTo($dashboardView);
        $role->givePermissionTo($gatewaysView);
        $role->givePermissionTo($coreView);
        // Support can likely view everything to help debug

        // admin
        $role = Role::findOrCreate('admin', 'web');
        $role->givePermissionTo(Permission::all());
    }
}

<?php

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display role-permission matrix. Admin role is hidden to prevent lockout.
     */
    public function index()
    {
        $roles = Role::whereNotIn('name', ['admin'])
            ->orderByRaw("CASE name WHEN 'free_user' THEN 1 WHEN 'pro_user' THEN 2 WHEN 'support' THEN 3 ELSE 4 END")
            ->get();

        $permissions = Permission::orderBy('name')->get();

        $userCountByRole = \App\Models\User::query()
            ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->where('model_has_roles.model_type', \App\Models\User::class)
            ->whereIn('model_has_roles.role_id', $roles->pluck('id'))
            ->selectRaw('model_has_roles.role_id, count(users.id) as total')
            ->groupBy('model_has_roles.role_id')
            ->pluck('total', 'role_id');

        return view('paneladmin::roles.index', compact('roles', 'permissions', 'userCountByRole'));
    }

    /**
     * Update role permissions.
     */
    public function update(Request $request)
    {
        // Expects an array of permissions for each role
        // format: permissions[role_id][] = permission_name

        $data = $request->validate([
            'permissions' => 'array',
        ]);

        $roles = Role::whereNotIn('name', ['admin'])->get();

        foreach ($roles as $role) {
            if (isset($data['permissions'][$role->id])) {
                $role->syncPermissions($data['permissions'][$role->id]);
            } else {
                // If no permissions sent, revoke all (for that role)
                $role->syncPermissions([]);
            }
        }

        return back()->with('success', 'Permissões atualizadas com sucesso!');
    }
}

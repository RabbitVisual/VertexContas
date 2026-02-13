<?php

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display role-permission matrix.
     */
    public function index()
    {
        $roles = Role::whereNotIn('name', ['admin'])->get(); // Hide admin to prevent lockout? Or allow edit? Better hide admin for safety in this iteration.
        // Actually, user requested: "Allow Admin to see permissions attached to free_user, pro_user, support".
        // So yes, exclude 'admin' from being edited here to adhere to "inviolable" nature or just filter it.
        // Let's filter 'admin' out to be safe.

        $permissions = Permission::all();

        return view('paneladmin::roles.index', compact('roles', 'permissions'));
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

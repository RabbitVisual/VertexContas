<?php

namespace Modules\PanelSuporte\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SupportAuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Modules\Core\Services\FinancialHealthService;

class UserManagementController extends Controller
{
    /**
     * Display the specified user profile.
     */
    public function show(User $user)
    {
        // Check if support access is granted
        if (! $user->support_access_expires_at || $user->support_access_expires_at->isPast()) {
            return redirect()->route('support.tickets.index')->with('error', 'Acesso ao perfil do usuário não autorizado ou expirado.');
        }

        $financialHealthService = app(FinancialHealthService::class);
        $financialSnapshot = $financialHealthService->getUserFinancialSnapshot($user);

        return view('panelsuporte::users.show', compact('user', 'financialSnapshot'));
    }

    /**
     * Show the form for editing the specified user profile.
     */
    public function edit(User $user)
    {
        // Check if support access is granted
        if (! $user->support_access_expires_at || $user->support_access_expires_at->isPast()) {
            return redirect()->route('support.tickets.index')->with('error', 'Acesso ao perfil do usuário não autorizado ou expirado.');
        }

        return view('panelsuporte::users.edit', compact('user'));
    }

    /**
     * Update the specified user profile.
     */
    public function update(Request $request, User $user)
    {
        // Check if support access is granted
        if (! $user->support_access_expires_at || $user->support_access_expires_at->isPast()) {
            return redirect()->route('support.tickets.index')->with('error', 'Acesso ao perfil do usuário não autorizado ou expirado.');
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'status' => ['required', 'in:active,inactive,blocked'],
        ]);

        $before = $user->only(array_keys($validated));
        $user->update($validated);
        $after = $user->only(array_keys($validated));

        // Audit Log
        SupportAuditLog::create([
            'agent_id' => Auth::id(),
            'user_id' => $user->id,
            'action' => 'profile_update',
            'metadata' => [
                'before' => $before,
                'after' => $after,
            ],
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('support.users.show', $user)->with('success', 'Perfil do usuário atualizado e log de auditoria gerado.');
    }
}

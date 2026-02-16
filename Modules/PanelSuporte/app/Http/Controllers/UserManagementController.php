<?php

namespace Modules\PanelSuporte\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SupportAuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Modules\Core\Models\Account;
use Modules\Core\Models\LegalDocument;
use Modules\Core\Models\Transaction;
use Modules\Core\Models\UserLegalAcceptance;
use Modules\Core\Services\FinancialHealthService;

class UserManagementController extends Controller
{
    /**
     * Display the specified user profile (User X-Ray).
     */
    public function show(User $user)
    {
        // Check if support access is granted
        if (! $user->support_access_expires_at || $user->support_access_expires_at->isPast()) {
            return redirect()->route('support.tickets.index')->with('error', 'Acesso ao perfil do usuário não autorizado ou expirado.');
        }

        // Audit: register profile view
        SupportAuditLog::create([
            'agent_id' => Auth::id(),
            'user_id' => $user->id,
            'action' => 'profile_detailed_view',
            'metadata' => ['reason' => 'Visualização de Perfil Detalhado'],
            'ip_address' => request()->ip(),
        ]);

        $financialHealthService = app(FinancialHealthService::class);
        $financialSnapshot = $financialHealthService->getUserFinancialSnapshot($user);
        $budgetHealth = $financialHealthService->getBudgetHealthAnalysis($user);
        $reserveMonths = $financialHealthService->getReserveMonths($user);

        $requiredDocs = LegalDocument::active()->requiresAcceptance()->orderBy('slug')->get();
        $complianceStatus = $requiredDocs->map(function (LegalDocument $doc) use ($user) {
            $acceptance = UserLegalAcceptance::where('user_id', $user->id)
                ->where('legal_document_id', $doc->id)
                ->first();
            $isUpToDate = $acceptance && $acceptance->version === $doc->version;

            return [
                'document' => $doc,
                'accepted_version' => $acceptance?->version,
                'accepted_at' => $acceptance?->accepted_at,
                'is_up_to_date' => $isUpToDate,
            ];
        });

        $accounts = Account::where('user_id', $user->id)->get();
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->with('category')
            ->latest('date')
            ->take(10)
            ->get();

        $recentAuditForUser = SupportAuditLog::where('user_id', $user->id)
            ->with('agent')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('panelsuporte::users.show', compact(
            'user',
            'financialSnapshot',
            'budgetHealth',
            'reserveMonths',
            'complianceStatus',
            'accounts',
            'recentTransactions',
            'recentAuditForUser'
        ));
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

        $request->merge([
            'phone' => lgpd_clean_phone($request->phone ?? null) ?: null,
            'birth_date' => parse_brl_date($request->birth_date ?? null) ?? $request->birth_date,
        ]);

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

    /**
     * Send password reset email to the user.
     */
    public function sendPasswordReset(User $user)
    {
        if (! $user->support_access_expires_at || $user->support_access_expires_at->isPast()) {
            return redirect()->route('support.tickets.index')->with('error', 'Acesso não autorizado ou expirado.');
        }

        $status = Password::sendResetLink(['email' => $user->email]);

        if ($status === Password::RESET_LINK_SENT) {
            SupportAuditLog::create([
                'agent_id' => Auth::id(),
                'user_id' => $user->id,
                'action' => 'password_reset_sent',
                'metadata' => ['reason' => 'Envio de link de redefinição de senha'],
                'ip_address' => request()->ip(),
            ]);

            return back()->with('success', 'Link de redefinição de senha enviado para o e-mail do usuário.');
        }

        return back()->with('error', 'Não foi possível enviar o link. Tente novamente.');
    }

    /**
     * Logout user from all devices (delete all sessions).
     */
    public function logoutAll(User $user)
    {
        if (! $user->support_access_expires_at || $user->support_access_expires_at->isPast()) {
            return redirect()->route('support.tickets.index')->with('error', 'Acesso não autorizado ou expirado.');
        }

        $deleted = DB::table('sessions')->where('user_id', $user->id)->delete();

        SupportAuditLog::create([
            'agent_id' => Auth::id(),
            'user_id' => $user->id,
            'action' => 'logout_all',
            'metadata' => ['reason' => 'Deslogar de todos os dispositivos', 'sessions_deleted' => $deleted],
            'ip_address' => request()->ip(),
        ]);

        return back()->with('success', 'Usuário foi deslogado de todos os dispositivos.');
    }
}

<?php

namespace Modules\PanelUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Modules\Core\Models\AccessLog;

class SecurityController extends Controller
{
    /**
     * Show security settings (password + logs).
     */
    public function index()
    {
        $logs = AccessLog::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('paneluser::security.index', compact('logs'));
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Senha alterada com sucesso!');
    }

    /**
     * Grant support access for 24 hours.
     */
    public function grantSupportAccess()
    {
        auth()->user()->update([
            'support_access_expires_at' => now()->addHours(24),
        ]);

        return back()->with('success', 'Acesso autorizado para a equipe de suporte pelas próximas 24 horas.');
    }

    /**
     * Revoke support access immediately.
     */
    public function revokeSupportAccess()
    {
        auth()->user()->update([
            'support_access_expires_at' => null,
        ]);

        return back()->with('success', 'Acesso da equipe de suporte revogado com sucesso.');
    }
}

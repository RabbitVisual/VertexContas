<?php

namespace Modules\PanelUser\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Modules\Core\Models\AccessLog;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    /**
     * Export access log as CSV (PRO only).
     */
    public function exportLogs(): StreamedResponse
    {
        if (!auth()->user()->isPro()) {
            abort(403, 'Recurso exclusivo para Vertex PRO.');
        }

        $logs = AccessLog::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'historico-acessos-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Data', 'Hora', 'IP', 'Navegador/Dispositivo', 'Localização'], ';');
            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->created_at->format('d/m/Y'),
                    $log->created_at->format('H:i:s'),
                    $log->ip_address,
                    Str::limit($log->user_agent, 80),
                    $log->location ?? '-',
                ], ';');
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}

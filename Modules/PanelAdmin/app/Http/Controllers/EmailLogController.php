<?php

declare(strict_types=1);

namespace Modules\PanelAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Core\Mail\VertexDynamicMail;
use Modules\Core\Models\EmailLog;

class EmailLogController extends Controller
{
    /**
     * List email logs ("Logs de Mensageria") with optional filters.
     */
    public function index(Request $request): View
    {
        $query = EmailLog::with('user')->latest('created_at');

        if ($request->filled('recipient_email')) {
            $query->where('recipient_email', 'like', '%' . $request->recipient_email . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('template_key')) {
            $query->where('template_key', $request->template_key);
        }
        if ($request->filled('from_date')) {
            $query->whereDate('sent_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('sent_at', '<=', $request->to_date);
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('paneladmin::mail.logs.index', compact('logs'));
    }

    /**
     * Return body_snapshot for a log (for "Visualizar" modal).
     */
    public function showBody(EmailLog $log)
    {
        $html = $log->body_snapshot ?? '<p class="text-slate-500">Conteúdo não disponível.</p>';
        return response($html)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    /**
     * Re-send a failed email (only when status is failed).
     * password_reset cannot be re-sent (token expired); user must request a new link.
     */
    public function resend(EmailLog $log)
    {
        if ($log->status !== 'failed') {
            return redirect()
                ->route('admin.mail.logs.index')
                ->with('error', 'Apenas registros com falha podem ser reenviados.');
        }

        if ($log->template_key === 'password_reset') {
            return redirect()
                ->route('admin.mail.logs.index')
                ->with('error', 'E-mail de recuperação de senha não pode ser reenviado. O usuário deve solicitar um novo link.');
        }

        $user = User::where('email', $log->recipient_email)->first();
        if (! $user) {
            return redirect()
                ->route('admin.mail.logs.index')
                ->with('error', 'Usuário não encontrado para o destinatário.');
        }

        $variables = [
            'name' => $user->name,
            'app_url' => config('app.url'),
            'link' => config('app.url'),
        ];
        $mailable = new VertexDynamicMail($log->template_key, $variables, $log->recipient_email);

        try {
            \Illuminate\Support\Facades\Mail::to($log->recipient_email)->send($mailable);
        } catch (\Throwable $e) {
            EmailLog::create([
                'user_id' => $user->id,
                'recipient_email' => $log->recipient_email,
                'template_key' => $log->template_key,
                'status' => 'failed',
                'error_details' => $e->getMessage(),
                'sent_at' => null,
            ]);

            return redirect()
                ->route('admin.mail.logs.index')
                ->with('error', 'Falha ao reenviar: ' . $e->getMessage());
        }

        return redirect()
            ->route('admin.mail.logs.index')
            ->with('success', 'E-mail reenviado com sucesso para ' . $log->recipient_email);
    }
}

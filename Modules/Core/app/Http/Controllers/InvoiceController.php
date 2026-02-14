<?php

declare(strict_types=1);

namespace Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Gateways\Models\PaymentLog;
use Modules\Gateways\Models\Subscription;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('pro');
    }

    /**
     * Lista faturas (pagamentos) do usuário PRO.
     */
    public function index()
    {
        $user = auth()->user();
        $invoices = PaymentLog::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Próxima cobrança: prioriza dados da assinatura ativa (Stripe/Mercado Pago)
        $activeSubscription = Subscription::where('user_id', $user->id)
            ->whereIn('status', ['active'])
            ->orderBy('current_period_end', 'desc')
            ->first();

        if ($activeSubscription && $activeSubscription->current_period_end) {
            $nextDue = $activeSubscription->current_period_end;
            $nextDueLabel = $nextDue->format('d/m/Y');
            $amount = number_format((float) $activeSubscription->amount, 2, ',', '.');
            $nextDueSubtext = "Próxima cobrança de R$ {$amount} será debitada nesta data. Assinatura mensal recorrente.";
        } else {
            // Fallback: último pagamento + 1 mês
            $lastPayment = PaymentLog::where('user_id', $user->id)
                ->where('status', 'succeeded')
                ->orderBy('created_at', 'desc')
                ->first();

            if ($lastPayment) {
                $nextDue = $lastPayment->created_at->copy()->addMonth();
                $nextDueLabel = $nextDue->format('d/m/Y');
                $nextDueSubtext = 'Próxima cobrança de R$ 29,90 será debitada nesta data. Assinatura mensal recorrente.';
            } else {
                $nextDue = null;
                $nextDueLabel = '—';
                $nextDueSubtext = 'Assinatura ativa. A próxima data de cobrança aparecerá após a confirmação do primeiro pagamento.';
            }
        }

        return view('core::invoices.index', compact('invoices', 'nextDue', 'nextDueLabel', 'nextDueSubtext'));
    }
}

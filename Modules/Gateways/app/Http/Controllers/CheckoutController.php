<?php

declare(strict_types=1);

namespace Modules\Gateways\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Modules\Core\Models\Plan;
use Modules\Gateways\Models\Gateway;
use Modules\Gateways\Services\GatewayFactory;

class CheckoutController extends Controller
{
    /** Valor mensal padrão quando plano não define amount. */
    private const DEFAULT_AMOUNT = 29.90;

    /**
     * Inicia o checkout de assinatura recorrente (Stripe Subscriptions / Mercado Pago PreApproval).
     * Aceita parâmetro plan (slug ou id) para plano dinâmico; senão usa plano pago padrão.
     */
    public function checkout(Request $request, string $gatewaySlug)
    {
        try {
            $gateway = Gateway::where('slug', $gatewaySlug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('user.subscription.index')
                ->with('error', 'Método de pagamento não configurado. Ative e configure em Administração > Gateways.');
        }

        if (! $gateway->is_active) {
            return redirect()->route('user.subscription.index')
                ->with('error', 'Este método de pagamento não está disponível no momento.');
        }

        if (! $gateway->secret_key) {
            return redirect()->route('user.subscription.index')
                ->with('error', 'Gateway ainda não configurado. O administrador deve preencher as chaves em Administração > Gateways.');
        }

        $plan = $this->resolvePlan($request);
        if (! $plan || $plan->is_free) {
            return redirect()->route('user.subscription.index')
                ->with('error', 'Plano inválido ou indisponível.');
        }

        $amount = $plan->amount ? (float) $plan->amount : self::DEFAULT_AMOUNT;

        try {
            $driver = GatewayFactory::make($gatewaySlug);
            $user = Auth::user();

            $metadata = [
                'user_id' => $user->id,
                'plan_type' => 'pro',
                'plan_id' => (string) $plan->id,
                'plan_slug' => $plan->slug,
                'email' => $user->email,
            ];
            if ($user->hasUsedTrial()) {
                $metadata['no_trial'] = true;
            }

            $redirectUrl = $driver->createCheckoutSession($amount, $metadata);

            return redirect()->away($redirectUrl);
        } catch (InvalidArgumentException $e) {
            return redirect()->route('user.subscription.index')
                ->with('error', 'Método de pagamento não suportado ou não configurado.');
        } catch (\Throwable $e) {
            return redirect()->route('user.subscription.index')
                ->with('error', 'Erro ao iniciar pagamento. Verifique as chaves do gateway no painel administrativo.');
        }
    }

    private function resolvePlan(Request $request): ?Plan
    {
        $planParam = $request->query('plan') ?? $request->query('plan_id');
        if (! $planParam) {
            return Plan::getDefaultPaid();
        }
        if (is_numeric($planParam)) {
            $plan = Plan::where('id', (int) $planParam)->where('is_active', true)->first();
        } else {
            $plan = Plan::where('slug', $planParam)->where('is_active', true)->first();
        }
        return $plan;
    }
}

<?php

declare(strict_types=1);

namespace Modules\Gateways\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Gateways\Models\Gateway;
use Modules\Gateways\Services\GatewayFactory;

class CheckoutController extends Controller
{
    /** Valor mensal do plano PRO (assinatura recorrente). */
    private const PRO_AMOUNT = 29.90;

    /**
     * Inicia o checkout de assinatura recorrente (Stripe Subscriptions / Mercado Pago PreApproval).
     */
    public function checkout(Request $request, string $gatewaySlug)
    {
        try {
            $gateway = Gateway::where('slug', $gatewaySlug)->firstOrFail();

            if (! $gateway->is_active) {
                return back()->with('error', 'Este método de pagamento não está disponível no momento.');
            }

            $driver = GatewayFactory::make($gatewaySlug);
            $user = Auth::user();

            $metadata = [
                'user_id' => $user->id,
                'plan_type' => 'pro',
                'email' => $user->email,
            ];

            $redirectUrl = $driver->createCheckoutSession(self::PRO_AMOUNT, $metadata);

            return redirect()->away($redirectUrl);
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao iniciar pagamento: ' . $e->getMessage());
        }
    }
}

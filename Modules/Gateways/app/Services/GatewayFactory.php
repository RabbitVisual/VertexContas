<?php

namespace Modules\Gateways\Services;

use Modules\Gateways\Contracts\PaymentGatewayInterface;
use Modules\Gateways\Drivers\StripeDriver;
use Modules\Gateways\Drivers\MercadoPagoDriver;
use InvalidArgumentException;

class GatewayFactory
{
    /**
     * Create a gateway driver instance.
     *
     * @param string $slug
     * @return PaymentGatewayInterface
     * @throws InvalidArgumentException
     */
    public static function make(string $slug): PaymentGatewayInterface
    {
        return match ($slug) {
            'stripe' => new StripeDriver(),
            'mercadopago' => new MercadoPagoDriver(),
            default => throw new InvalidArgumentException("Gateway driver not found for: {$slug}"),
        };
    }
}

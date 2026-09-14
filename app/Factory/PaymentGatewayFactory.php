<?php

namespace App\Factory;

use App\Enums\PaymentGatewayProvider;
use App\Interfaces\IPaymentGateway;
use App\Repositories\RBorderpayGateway;
use InvalidArgumentException;

class PaymentGatewayFactory
{
    public static function make(
        PaymentGatewayProvider $provider
    ): IPaymentGateway {
        return match ($provider) {
            PaymentGatewayProvider::BORDERPAY => app(RBorderpayGateway::class),

            default => throw new InvalidArgumentException(
                "Unsupported payment gateway: {$provider->value}"
            ),
        };
    }
}

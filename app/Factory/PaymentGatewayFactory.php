<?php

namespace App\Factory;

use App\Enums\PaymentGatewayType;
use App\Interfaces\IPaymentGateway;
use App\Repositories\RBorderpayGateway;
use InvalidArgumentException;

class PaymentGatewayFactory
{
    public static function make(
        PaymentGatewayType $type
    ): IPaymentGateway {
        return match ($type) {
            PaymentGatewayType::BORDERPAY => app(RBorderpayGateway::class),

            default => throw new InvalidArgumentException(
                "Unsupported payment gateway: {$type->value}"
            ),
        };
    }
}

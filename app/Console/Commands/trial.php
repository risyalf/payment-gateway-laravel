<?php

namespace App\Console\Commands;

use App\Enums\PaymentGatewayType;
use App\Factory\PaymentGatewayFactory;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('trial')]
#[Description('Command description')]
class trial extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pg = PaymentGatewayFactory::make(PaymentGatewayType::BORDERPAY);

        $pg->getPaymentMethod();
    }
}

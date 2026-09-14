<?php

namespace App\Console\Commands;

use App\Actions\SyncPaymentMethod;
use App\Enums\PaymentGatewayProvider;
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
        SyncPaymentMethod::execute(PaymentGatewayProvider::BORDERPAY);
    }
}

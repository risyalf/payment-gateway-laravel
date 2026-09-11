<?php

namespace App\Repositories;

use App\Interfaces\IPaymentGateway;
use Illuminate\Support\Facades\Http;

class RBorderpayGateway implements IPaymentGateway
{
    protected string $baseUrl = "https://borderpay.id/api/v1";

    public function getPaymentMethod()
    {
        $url = "/payment-methods";
        $fullUrl = $this->baseUrl . $url;

        $response = Http::withToken(config("services.payment_gateway_keys.borderpay"))
            ->get($fullUrl);

        $json = $response->json();

        dd($json);
    }
}

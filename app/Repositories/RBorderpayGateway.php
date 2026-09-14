<?php

namespace App\Repositories;

use App\DTO\DataPaymentMethodDTO;
use App\Enums\PaymentGatewayProvider;
use App\Interfaces\IPaymentGateway;
use Exception;
use Illuminate\Support\Facades\Http;

class RBorderpayGateway implements IPaymentGateway
{
    protected string $baseUrl = "https://borderpay.id/api/v1";

    public function getPaymentMethod()
    {
        $url = "/payment-methods";
        $fullUrl = $this->baseUrl . $url;
        $token = config("services.payment_gateway_keys.borderpay");

        $response = Http::withToken($token)
            ->get($fullUrl);

        if ($response->status() == 200) {
            $datas = $response->json();

            $provider = PaymentGatewayProvider::BORDERPAY;
            $paymentMethods = [];
            $counter = 0;
            foreach ($datas as $key => $data) {
                if ($key == 'qris') {
                    $paymentMethods[$counter]['method'] = $key;
                    $paymentMethods[$counter]['code'] = $key;
                    $paymentMethods[$counter]['name'] = $key;
                    $paymentMethods[$counter]['enabled'] = $data['enabled'];
                    $paymentMethods[$counter]['fees'][] = [
                        'min_amount' => 0,
                        'percent' => $data['fee']['percent'],
                        'flat' => $data['fee']['flat'],
                    ];
                    foreach ($data['fee']['tiers'] as $fee) {
                        $paymentMethods[$counter]['fees'][] = [
                            'min_amount' => $fee['minAmount'],
                            'percent' => $fee['percent'],
                            'flat' => $fee['flat'],
                        ];
                    }

                    $counter++;
                } else if ($key == 'va') {
                    foreach ($data['banks'] as $method => $bank) {
                        $paymentMethods[$counter]['method'] = $key;
                        $paymentMethods[$counter]['code'] = $bank['code'];
                        $paymentMethods[$counter]['name'] = $bank['name'];
                        $paymentMethods[$counter]['enabled'] = $bank['enabled'];
                        $paymentMethods[$counter]['fees'][] = [
                            'min_amount' => 0,
                            'percent' => $bank['fee']['percent'],
                            'flat' => $bank['fee']['flat'],
                        ];

                        $counter++;
                    }
                } else if ($key == 'ewallet') {
                    foreach ($data['wallets'] as $wallet) {
                        $paymentMethods[$counter]['method'] = $key;
                        $paymentMethods[$counter]['code'] = $wallet['code'];
                        $paymentMethods[$counter]['name'] = $wallet['name'];
                        $paymentMethods[$counter]['enabled'] = $wallet['enabled'];

                        $paymentMethods[$counter]['fees'][] = [
                            'min_amount' => 0,
                            'percent' => $wallet['fee']['percent'],
                            'flat' => $wallet['fee']['flat'],
                        ];

                        foreach ($wallet['fee']['tiers'] ?? [] as $fee) {
                            $paymentMethods[$counter]['fees'][] = [
                                'min_amount' => $fee['minAmount'],
                                'percent' => $fee['percent'],
                                'flat' => $fee['flat'],
                            ];
                        }

                        $counter++;
                    }
                }
            }

            $provider = PaymentGatewayProvider::BORDERPAY->value;

            return collect($paymentMethods)->map(fn($data) => DataPaymentMethodDTO::fromArray([
                'provider' => $provider,
                'method' => $data['method'],
                'code' => $data['code'],
                'name' => $data['name'],
                'enabled' => $data['enabled'],
                'fees' => $data['fees'],
            ]));
        }

        throw new Exception($response->json());
    }
}

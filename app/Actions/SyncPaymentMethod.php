<?php

namespace App\Actions;

use App\Enums\PaymentGatewayProvider;
use App\Factory\PaymentGatewayFactory;
use App\Models\PaymentMethod;
use App\Models\PaymentMethodFee;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SyncPaymentMethod
{
    public static function execute(PaymentGatewayProvider $provider)
    {
        $paymentGateway = PaymentGatewayFactory::make($provider);

        $datas = $paymentGateway->getPaymentMethod();

        foreach ($datas as $key => $data) {
            $paymentMethod = PaymentMethod::query()
                ->updateOrCreate([
                    'provider' => $data->provider,
                    'method' => $data->method,
                    'code' => $data->code,
                ], [
                    'name' => $data->name,
                    'enabled' => $data->enabled
                ]);

            PaymentMethodFee::query()
                ->where('payment_method_id', $paymentMethod->id)
                ->delete();

            $fees = [];

            foreach ($data->fees as $key => $fee) {
                $fees[] = [
                    "id" => Str::uuid7(),
                    "created_at" => Carbon::now()->toDateTimeString(),
                    "updated_at" => Carbon::now()->toDateTimeString(),
                    "payment_method_id" => $paymentMethod->id,
                    "min_amount" => $fee['min_amount'],
                    "percent" => $fee['percent'],
                    "flat" => $fee['flat'],
                ];
            }

            if (!empty($fees)) {
                PaymentMethodFee::insert($fees);
            }
        }
    }
}

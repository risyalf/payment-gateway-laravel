<?php

namespace App\DTO;

class DataPaymentMethodDTO
{
    public function __construct(
        public string $provider,
        public string $method,
        public string $code,
        public string $name,
        public bool $enabled,
        public array $fees,
    ) {}

    public static function fromArray(array $data)
    {
        return new self(
            provider: $data['provider'],
            method: $data['method'],
            code: $data['code'],
            name: $data['name'],
            enabled: $data['enabled'],
            fees: $data['fees'],
        );
    }
}

<?php

namespace App\Interfaces;

interface IPaymentGateway
{
    public function getPaymentMethod();
}

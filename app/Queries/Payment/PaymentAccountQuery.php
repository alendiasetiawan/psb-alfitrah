<?php

namespace App\Queries\Payment;

use App\Models\Payment\PaymentAccount;

class PaymentAccountQuery
{
    public static function fetchRegistrationAccount(): ?PaymentAccount
    {
        return PaymentAccount::baseEloquent()->first();
    }
}

<?php

namespace App\Services;

use App\Models\Payment\PaymentAccount;
use App\Queries\Payment\PaymentAccountQuery;

class PaymentAccountService
{
    /** @return array{bank_name: string, account_number: string, account_name: string} */
    public function registrationAccount(): array
    {
        $account = PaymentAccountQuery::fetchRegistrationAccount();

        if ($account) {
            return [
                'bank_name' => $account->bank_name,
                'account_number' => $account->account_number,
                'account_name' => $account->account_name,
            ];
        }

        return [
            'bank_name' => (string) config('services.registration_payment.bank_name'),
            'account_number' => (string) config('services.registration_payment.account_number'),
            'account_name' => (string) config('services.registration_payment.account_name'),
        ];
    }

    public function saveRegistrationAccount(string $bankName, string $accountNumber, string $accountName): void
    {
        PaymentAccount::query()->updateOrCreate(
            ['id' => PaymentAccount::REGISTRATION_ID],
            ['bank_name' => $bankName, 'account_number' => $accountNumber, 'account_name' => $accountName],
        );
    }
}

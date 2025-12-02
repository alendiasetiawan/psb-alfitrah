<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class XenditService
{
    public function __construct()
    {
        $this->baseUrl = config('services.xendit.base_url');
        $this->secretKey = config('services.xendit.secret_key');
    }

    public function createInvoice($transaction)
    {
        $payload = [
            'external_id' => $transaction->external_id,
            'amount' => $transaction->amount,
            'description' => $transaction->description,
            'success_redirect_url' => route('student.payment.registration_payment'),
            'failure_redirect_url' => route('student.payment.registration_payment'),
            'expiration_date' => $transaction->expiry_date,
            'locale' => 'id'
        ];

        $response = Http::withBasicAuth($this->secretKey, '')->post($this->baseUrl . '/v2/invoices', $payload);

        return $response->json();
    }
}

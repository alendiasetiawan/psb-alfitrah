<?php

namespace App\Http\Controllers\PaymentGateway;

use App\Enums\PaymentStatusEnum;
use App\Enums\VerificationStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\AdmissionData\AdmissionVerification;
use App\Models\AdmissionData\RegistrationPayment;
use App\Models\Payment\InvoiceLog;
use App\Models\Payment\RegistrationInvoice;
use Illuminate\Http\Request;

class XenditWebhookController extends Controller
{
    public function confirmInvoice(Request $request)
    {
        // 2. Simpan log webhook
        InvoiceLog::create([
            'external_id' => $request->external_id,
            'payload'     => json_encode($request->all()),
            'payment_method' => $request->payment_channel
        ]);

        // 3. Cari transaksi internal
        $trx = RegistrationInvoice::where('external_id', $request->external_id)->first();

        if (!$trx) {
            return response()->json(['error' => 'Transaction not found'], 404);
        } else {
            $studentId = $trx->student_id;
            $payment = RegistrationPayment::where('student_id', $studentId)->first();
            $verification = AdmissionVerification::where('student_id', $studentId)->first();

            // 4. Update status
            switch ($request->status) {
                case 'PAID':
                    $trx->update([
                        'status'       => PaymentStatusEnum::PAID,
                        'paid_at'      => now(),
                        'raw_callback' => json_encode($request->all()),
                        'payment_method' => $request->payment_channel,
                    ]);

                    $payment->update([
                        'payment_status' => VerificationStatusEnum::VALID,
                    ]);

                    $verification->update([
                        'registration_payment' => VerificationStatusEnum::VALID,
                    ]);
                    break;

                case 'EXPIRED':
                    $trx->update([
                        'status' => PaymentStatusEnum::EXPIRED
                    ]);

                    $payment->update([
                        'payment_status' => PaymentStatusEnum::EXPIRED,
                    ]);
                    break;

                case 'FAILED':
                    $trx->update([
                        'status' => PaymentStatusEnum::FAILED
                    ]);

                    $payment->update([
                        'payment_status' => VerificationStatusEnum::INVALID,
                    ]);

                    $verification->update([
                        'registration_payment' => VerificationStatusEnum::INVALID
                    ]);
                    break;
            }

            return response()->json(['success' => true]);
        }
    }
}

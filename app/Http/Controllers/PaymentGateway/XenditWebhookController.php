<?php

namespace App\Http\Controllers\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Models\AdmissionData\AdmissionVerification;
use App\Models\AdmissionData\RegistrationPayment;
use App\Models\Payment\InvoiceLog;
use App\Models\Payment\RegistrationInvoice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class XenditWebhookController extends Controller
{
    public function confirmInvoice(Request $request)
    {
        // 2. Simpan log webhook
        InvoiceLog::create([
            'event'       => $request->event,
            'external_id' => $request->external_id,
            'payload'     => json_encode($request->all()),
        ]);

        // 3. Cari transaksi internal
        $trx = RegistrationInvoice::where('external_id', $request->external_id)->first();

        if (!$trx) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        // 4. Update status
        switch ($request->status) {
            case 'PAID':
                $trx->update([
                    'status'       => 'PAID',
                    'paid_at'      => now(),
                    'raw_callback' => json_encode($request->all()),
                    'payment_method' => $request->payment_channel
                ]);
                break;

            case 'EXPIRED':
                $trx->update(['status' => 'EXPIRED']);
                break;

            case 'FAILED':
                $trx->update(['status' => 'FAILED']);
                break;
        }

        // 5. Update Verification and Payment Status
        $studentId = $trx->student_id;
        RegistrationPayment::where('student_id', $studentId)->update([
            'payment_status' => "Valid"
        ]);

        AdmissionVerification::where('student_id', $studentId)->update([
            'registration_payment' => "Valid"
        ]);

        return response()->json(['success' => true]);
    }
}

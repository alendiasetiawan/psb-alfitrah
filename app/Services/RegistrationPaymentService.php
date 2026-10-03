<?php

namespace App\Services;

use App\Enums\VerificationStatusEnum;
use App\Queries\Payment\RegistrationPaymentQuery;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use RuntimeException;
use Throwable;

class RegistrationPaymentService
{
    public function __construct(protected UploadFileService $uploadFileService) {}

    public function submitEvidence(int $studentId, int $userId, UploadedFile $evidence): void
    {
        $newPath = null;
        $oldPath = null;
        $isCommitted = false;

        try {
            DB::transaction(function () use ($studentId, $userId, $evidence, &$newPath, &$oldPath, &$isCommitted): void {
                $payment = RegistrationPaymentQuery::fetchPaymentForUpdate($studentId, $userId);

                if ($payment->payment_status === VerificationStatusEnum::VALID || ($payment->payment_status === VerificationStatusEnum::PROCESS && $payment->evidence)) {
                    throw ValidationException::withMessages([
                        'evidence' => 'Pembayaran sudah valid atau bukti transfer sedang diperiksa admin.',
                    ]);
                }

                $verification = $payment->student->admissionVerification;
                abort_unless($verification, 404);

                $oldPath = $payment->evidence;
                $newPath = $this->uploadFileService->compressAndSavePhoto($evidence, 'registration-payments/'.$studentId);

                if (! Storage::disk('public')->exists($newPath)) {
                    throw new RuntimeException('Bukti transfer gagal disimpan.');
                }

                DB::afterCommit(function () use (&$isCommitted, $newPath, $oldPath): void {
                    $isCommitted = true;

                    if ($oldPath && $oldPath !== $newPath) {
                        try {
                            Storage::disk('public')->delete($oldPath);
                        } catch (Throwable $exception) {
                            report($exception);
                        }
                    }
                });

                $payment->update([
                    'evidence' => $newPath,
                    'payment_status' => VerificationStatusEnum::PROCESS,
                ]);

                $verification->update([
                    'registration_payment' => VerificationStatusEnum::PROCESS,
                    'payment_error_msg' => null,
                ]);
            });
        } catch (Throwable $exception) {
            if ($isCommitted) {
                report($exception);

                return;
            }

            if ($newPath) {
                Storage::disk('public')->delete($newPath);
            }

            throw $exception;
        }
    }

    public function verifyEvidence(int $studentId, string $status, string $expectedEvidence, ?string $invalidReason = null): void
    {
        if (! in_array($status, [VerificationStatusEnum::VALID, VerificationStatusEnum::INVALID], true)) {
            throw ValidationException::withMessages(['paymentStatus' => 'Pilih status Valid atau Tidak Valid.']);
        }

        if ($status === VerificationStatusEnum::INVALID && blank($invalidReason)) {
            throw ValidationException::withMessages(['invalidReason' => 'Alasan penolakan wajib diisi.']);
        }

        $isCommitted = false;

        try {
            DB::transaction(function () use ($studentId, $status, $expectedEvidence, $invalidReason, &$isCommitted): void {
                $payment = RegistrationPaymentQuery::fetchPaymentForUpdate($studentId);

                if ($payment->payment_status !== VerificationStatusEnum::PROCESS || ! $payment->evidence || $payment->evidence !== $expectedEvidence) {
                    throw ValidationException::withMessages(['paymentStatus' => 'Bukti transfer telah berubah atau sudah diverifikasi. Tutup dan buka kembali verifikasi pembayaran.']);
                }

                $verification = $payment->student->admissionVerification;
                abort_unless($verification, 404);

                DB::afterCommit(function () use (&$isCommitted): void {
                    $isCommitted = true;
                });

                $payment->update(['payment_status' => $status]);
                $verification->update([
                    'registration_payment' => $status,
                    'payment_error_msg' => $status === VerificationStatusEnum::INVALID ? trim($invalidReason) : null,
                ]);
            });
        } catch (Throwable $exception) {
            if (! $isCommitted) {
                throw $exception;
            }

            report($exception);
        }
    }
}

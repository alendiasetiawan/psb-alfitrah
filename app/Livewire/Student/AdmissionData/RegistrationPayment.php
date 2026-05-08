<?php

namespace App\Livewire\Student\AdmissionData;

use App\Enums\VerificationStatusEnum;
use App\Helpers\AdmissionHelper;
use App\Helpers\CodeGeneratorHelper;
use App\Helpers\DateFormatHelper;
use App\Helpers\FormatCurrencyHelper;
use App\Helpers\MessageHelper;
use App\Helpers\WhaCenterHelper;
use App\Models\AdmissionData\AdmissionVerification;
use App\Models\AdmissionData\RegistrationPayment as AdmissionDataRegistrationPayment;
use App\Models\Payment\RegistrationInvoice;
use App\Queries\Payment\RegistrationInvoiceQuery;
use App\Queries\Payment\RegistrationPaymentQuery;
use App\Services\StudentDataService;
use App\Services\XenditService;
use Carbon\Carbon;
use Detection\MobileDetect;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Biaya Pendaftaran')]
class RegistrationPayment extends Component
{
    public bool $isMobile = false, $isPendingPayment = false, $isHasActiveInvoice = false;
    public int $studentId;
    public string $admissionName;
    public float $amount;

    protected XenditService $xenditService;


    #[Computed]
    public function detailPayment()
    {
        return RegistrationInvoiceQuery::fetchLatestPayment($this->studentId);
    }

    //HOOK - Execute once when component is rendered
    public function mount(MobileDetect $mobileDetect, StudentDataService $studentDataService)
    {
        $this->isMobile = $mobileDetect->isMobile();

        //Defind student_id
        $parentId = session('userData')->parent->id;
        $this->studentId = $studentDataService->findActiveStudentId($parentId);

        //Defind admission name
        $activeAdmission = AdmissionHelper::activeAdmission();
        $this->admissionName = $activeAdmission->name;

        //Define amount to pay based on registration data
        $this->amount = RegistrationPaymentQuery::fetchStudentPayment($this->studentId)->amount;

        //Define if there is pending payment
        if ($this->detailPayment->registrationInvoices->count() != 0) {
            $this->isHasActiveInvoice = true;
        } else {
            $this->isHasActiveInvoice = false;
        }
    }

    //HOOK - Execute every time component is rendered
    public function boot(XenditService $xenditService)
    {
        $this->xenditService = $xenditService;
    }

    //ACTION - Create invoice for first paymment
    public function createInvoice()
    {
        try {
            $expiryDate = Carbon::now()->addHours(6)->toIso8601String();
            $userExpiryDate = DateFormatHelper::indoDateTime($expiryDate);

            DB::transaction(function () use ($expiryDate, $userExpiryDate) {
                //Make internal invoice
                $transaction = RegistrationInvoice::create([
                    'username' => session('userData')->username,
                    'student_id' => $this->studentId,
                    'external_id' => CodeGeneratorHelper::registrationInvoiceNumber($this->admissionName),
                    'amount' => $this->amount,
                    'description' => 'Biaya Pendaftaran Siswa Baru a/n ' . session('userData')->fullname . ' di ' . $this->detailPayment->branch_name . ' Program ' . $this->detailPayment->program_name . '',
                    'expiry_date' => $expiryDate,
                ]);

                //Fetch invoice from xendit
                $invoice = $this->xenditService->createInvoice($transaction);
                $transaction->update([
                    'invoice_id' => $invoice['id'],
                    'payment_url' => $invoice['invoice_url'],
                ]);

                //Update internal payment status
                AdmissionDataRegistrationPayment::where('student_id', $this->studentId)->update([
                    'payment_status' => VerificationStatusEnum::PROCESS
                ]);
                AdmissionVerification::where('student_id', $this->studentId)->update([
                    'registration_payment' => VerificationStatusEnum::PROCESS
                ]);

                //Send notification to user
                $amountRupiah = FormatCurrencyHelper::convertToRupiah($this->amount);

                $message = MessageHelper::waInvoiceCreated(session('userData')->fullname, $amountRupiah, $this->detailPayment->branch_name, $this->detailPayment->program_name, $this->detailPayment->academic_year, $userExpiryDate);
                WhaCenterHelper::sendText(session('userData')->mobile_phone, $message);

                $this->redirect(route('student.payment.registration_payment'), navigate: true);
            });
        } catch (\Throwable $th) {
            logger($th);
            session()->flash('create-invoice-failed', 'Upss.. terjadi kesalahan, silahkan coba beberapa saat lagi!');
        }
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.student.admission-data.registration-payment')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => true
            ]);;
        }
        return view('livewire.web.student.admission-data.registration-payment')->layout('components.layouts.web.web-app');
    }
}

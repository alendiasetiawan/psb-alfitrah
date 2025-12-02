<?php

namespace App\Livewire\Student\AdmissionData;

use Livewire\Component;
use Detection\MobileDetect;
use Livewire\Attributes\Title;
use App\Services\XenditService;
use App\Helpers\AdmissionHelper;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\DB;
use App\Helpers\CodeGeneratorHelper;
use App\Services\StudentDataService;
use App\Models\Payment\RegistrationInvoice;
use Symfony\Component\HttpKernel\Log\Logger;
use App\Queries\Payment\RegistrationInvoiceQuery;
use App\Queries\Payment\RegistrationPaymentQuery;
use Carbon\Carbon;

#[Title('Biaya Pendaftaran')]
class RegistrationPayment extends Component
{
    public bool $isMobile = false, $isPendingPayment = false;
    public int $studentId;
    public string $admissionName;
    public float $amount;

    protected XenditService $xenditService;


    #[Computed]
    public function detailPayment() {
        return RegistrationInvoiceQuery::fetchLatestPayment($this->studentId);
    }

    //HOOK - Execute once when component is rendered
    public function mount(MobileDetect $mobileDetect, StudentDataService $studentDataService) {
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
        if  ($this->detailPayment->registrationInvoices->count() != 0) {
            if ($this->detailPayment->registrationInvoices[0]->status == \App\Enums\PaymentStatusEnum::PENDING) {
                $this->isPendingPayment = true;
            } else {
                $this->isPendingPayment = false;
            }
        } else {
            $this->isPendingPayment = false;
        }
        
    }

    //HOOK - Execute every time component is rendered
    public function boot(XenditService $xenditService) {
        $this->xenditService = $xenditService;
    }

    //ACTION - Create invoice for first paymment
    public function createInvoice()
    {
        try {
            DB::transaction(function () {
                $transaction = RegistrationInvoice::create([
                    'username' => session('userData')->username,
                    'student_id' => $this->studentId,
                    'external_id' => CodeGeneratorHelper::registrationInvoiceNumber($this->admissionName),
                    'amount' => $this->amount,
                    'description' => 'Biaya Pendaftaran Siswa Baru a/n '.session('userData')->fullname.' di '.$this->detailPayment->branch_name.' Program '.$this->detailPayment->program_name.'',
                    'expiry_date' => Carbon::now()->addHours(6)->toIso8601String(),
                ]);
        
                $invoice = $this->xenditService->createInvoice($transaction);
        
                $transaction->update([
                    'invoice_id' => $invoice['id'],
                    'payment_url' => $invoice['invoice_url'],
                ]);

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

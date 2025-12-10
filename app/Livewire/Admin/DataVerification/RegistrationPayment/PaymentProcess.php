<?php

namespace App\Livewire\Admin\DataVerification\RegistrationPayment;

use App\Enums\PaymentStatusEnum;
use App\Enums\VerificationStatusEnum;
use App\Helpers\AdmissionHelper;
use App\Queries\Payment\RegistrationPaymentQuery;
use Detection\MobileDetect;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Proses Bayar Pendaftaran')]
class PaymentProcess extends Component
{
    public bool $isMobile = false;
    public string $searchStudent = '';
    public ?int $selectedAdmissionId = null, $limitData = 9;

    #[Computed]
    public function processStudentLists()
    {
        return RegistrationPaymentQuery::queryPaymentVerification($this->selectedAdmissionId, $this->searchStudent)
            ->whereIn('registration_payments.payment_status', [VerificationStatusEnum::PROCESS, PaymentStatusEnum::EXPIRED])
            ->with([
                'registrationInvoices' => function ($query) {
                    $query->addSelect('student_id', 'invoice_id', 'expiry_date')
                        ->orderBy('id', 'desc')
                        ->limit(1);
                }
            ])
            ->paginate($this->limitData);
    }

    #[Computed]
    public function totalProcessStudent()
    {
        return RegistrationPaymentQuery::countTotalPaymentProcess($this->selectedAdmissionId);
    }

    public function mount()
    {
        $queryAdmission = AdmissionHelper::activeAdmission();
        $this->selectedAdmissionId = $queryAdmission->id;
    }

    public function boot(MobileDetect $mobileDetect)
    {
        $this->isMobile = $mobileDetect->isMobile();
    }


    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.data-verification.registration-payment.payment-process')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => true,
            ]);
        }
        return view('livewire.web.admin.data-verification.registration-payment.payment-process')->layout('components.layouts.web.web-app');
    }
}

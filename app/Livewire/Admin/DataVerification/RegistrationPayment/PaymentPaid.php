<?php

namespace App\Livewire\Admin\DataVerification\RegistrationPayment;

use App\Enums\VerificationStatusEnum;
use App\Helpers\AdmissionHelper;
use App\Queries\Payment\RegistrationPaymentQuery;
use Detection\MobileDetect;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Selesai Bayar Pendaftaran')]
class PaymentPaid extends Component
{
    use WithPagination;

    public bool $isMobile;
    public object $admissionYearLists;
    public string $searchStudent = '';
    public ?int $selectedAdmissionId = null, $limitData = 10, $setCount = 1, $filterAdmissionId;

    #[Computed]
    public function paidStudentLists()
    {
        return RegistrationPaymentQuery::paginatePaidStudent($this->selectedAdmissionId, $this->searchStudent, $this->limitData);
    }

    #[Computed]
    public function totalIncome()
    {
        return RegistrationPaymentQuery::sumIncomeRegistrationPayment($this->selectedAdmissionId);
    }

    public function mount()
    {
        $queryAdmission = AdmissionHelper::activeAdmission();
        $this->selectedAdmissionId = $queryAdmission->id;
        $this->admissionYearLists = AdmissionHelper::getAdmissionYearLists();
    }

    public function boot(MobileDetect $mobileDetect)
    {
        $this->isMobile = $mobileDetect->isMobile();
    }

    //ANCHOR: Execute when page number is updated
    public function updatedPage($page)
    {
        $setPage = $page - 1;
        $dataLoaded = $setPage * $this->limitData;
        $this->setCount = $dataLoaded + 1;
    }

    //ANCHOR: SET Value Filter
    public function setSelectedAdmissionId()
    {
        $this->selectedAdmissionId = $this->filterAdmissionId;
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.data-verification.registration-payment.payment-paid')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => true,
            ]);
        }

        return view('livewire.web.admin.data-verification.registration-payment.payment-paid')->layout('components.layouts.web.web-app');
    }
}

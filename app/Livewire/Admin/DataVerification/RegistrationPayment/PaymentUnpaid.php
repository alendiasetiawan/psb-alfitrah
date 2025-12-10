<?php

namespace App\Livewire\Admin\DataVerification\RegistrationPayment;

use App\Enums\FollowUpStatusEnum;
use App\Enums\VerificationStatusEnum;
use App\Helpers\AdmissionHelper;
use App\Helpers\FormatCurrencyHelper;
use App\Helpers\MessageHelper;
use App\Helpers\WhaCenterHelper;
use App\Models\AdmissionData\AdmissionVerification;
use App\Queries\AdmissionData\StudentQuery;
use App\Queries\Payment\RegistrationPaymentQuery;
use Detection\MobileDetect;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Belum Bayar Pendaftaran')]
class PaymentUnpaid extends Component
{
    public bool $isMobile = false;
    public object $admissionYearLists;
    public string $searchStudent = '';
    public ?int $selectedAdmissionId = null, $limitData = 9;

    //ANCHOR: ACTION LOAD MORE
    #[On('load-more')]
    public function loadMore($loadItem)
    {
        $this->limitData += $loadItem;
    }

    #[Computed]
    public function notPaidStudentLists()
    {
        return RegistrationPaymentQuery::queryPaymentVerification($this->selectedAdmissionId, $this->searchStudent)
            ->where('registration_payments.payment_status', VerificationStatusEnum::NOT_STARTED)
            ->paginate($this->limitData);
    }

    #[Computed]
    public function totalStudent()
    {
        return RegistrationPaymentQuery::countTotalPaymentNotStarted($this->selectedAdmissionId);
    }

    public function boot(MobileDetect $mobileDetect)
    {
        $this->isMobile = $mobileDetect->isMobile();
    }

    public function mount()
    {
        $queryAdmission = AdmissionHelper::activeAdmission();
        $this->selectedAdmissionId = $queryAdmission->id;
        $this->admissionYearLists = AdmissionHelper::getAdmissionYearLists();
    }

    public function updated($property, $value)
    {
        if ($property == 'selectedAdmissionId') {
            $this->selectedAdmissionId = $value;
        }
    }

    //ANCHOR: ACTION FOLLOW UP PAYMENT
    public function fuPayment($studentId)
    {
        $studentQuery = StudentQuery::fetchDetailRegistrant($studentId);
        $waNumber = $studentQuery->country_code . $studentQuery->mobile_phone;
        $amount = FormatCurrencyHelper::convertCurrency($studentQuery->registration_fee);

        try {
            DB::transaction(function () use ($studentId, $studentQuery, $waNumber, $amount) {
                //Send WA Message
                $message = MessageHelper::waFollowUpPayment($studentQuery->student_name, $studentQuery->branch_name, $studentQuery->program_name, $studentQuery->academic_year, $amount);

                WhaCenterHelper::sendText($waNumber, $message);

                //Update fu status
                AdmissionVerification::where('student_id', $studentId)->update([
                    'fu_payment' => FollowUpStatusEnum::DONE,
                ]);
            });

            $this->dispatch('toast', type: 'success', message: 'Pesan berhasil dikirim');
            $this->notPaidStudentLists();
        } catch (\Throwable $th) {
            session()->flash('error-fu-payment', 'Upss.. Pengiriman pesan gagal, silahkan coba lagi nanti!');
        }
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.data-verification.registration-payment.payment-unpaid')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => true,
            ]);
        }

        return view('livewire.web.admin.data-verification.registration-payment.payment-unpaid')->layout('components.layouts.web.web-app');
    }
}

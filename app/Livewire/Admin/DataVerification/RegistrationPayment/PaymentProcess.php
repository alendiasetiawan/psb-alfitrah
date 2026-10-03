<?php

namespace App\Livewire\Admin\DataVerification\RegistrationPayment;

use App\Enums\RoleEnum;
use App\Enums\VerificationStatusEnum;
use App\Helpers\AdmissionHelper;
use App\Models\AdmissionData\RegistrationPayment;
use App\Queries\Payment\RegistrationPaymentQuery;
use App\Services\RegistrationPaymentService;
use Detection\MobileDetect;
use Flux\Flux;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Throwable;

#[Title('Proses Bayar Pendaftaran')]
class PaymentProcess extends Component
{
    use WithPagination;

    public bool $isMobile = false;

    public string $searchStudent = '';

    public string $paymentStatus = '';

    public string $invalidReason = '';

    public ?int $selectedAdmissionId = null;

    public ?int $limitData = 9;

    #[Locked]
    public ?int $selectedStudentId = null;

    #[Locked]
    public ?string $selectedEvidence = null;

    #[On('load-more')]
    public function loadMore(int $loadItem): void
    {
        $this->limitData += $loadItem;
    }

    #[Computed]
    public function processStudentLists(): LengthAwarePaginator
    {
        return RegistrationPaymentQuery::paginateProcessStudent($this->selectedAdmissionId, $this->searchStudent, $this->limitData);
    }

    #[Computed]
    public function totalProcessStudent(): int
    {
        return RegistrationPaymentQuery::countTotalPaymentProcess($this->selectedAdmissionId);
    }

    #[Computed]
    public function selectedPayment(): ?RegistrationPayment
    {
        if ($this->selectedStudentId === null) {
            return null;
        }

        return RegistrationPaymentQuery::fetchPaymentForVerification($this->selectedStudentId, $this->selectedAdmissionId);
    }

    public function mount(): void
    {
        $this->authorizeAdmin();
        $this->selectedAdmissionId = AdmissionHelper::activeAdmission()->id;
    }

    public function boot(MobileDetect $mobileDetect): void
    {
        $this->isMobile = $mobileDetect->isMobile();
    }

    public function updatedSearchStudent(): void
    {
        $this->resetPage();
    }

    public function openVerification(int $studentId): void
    {
        $this->authorizeAdmin();
        $payment = RegistrationPaymentQuery::fetchPaymentForVerification($studentId, $this->selectedAdmissionId);
        abort_unless($payment->evidence && $payment->payment_status === VerificationStatusEnum::PROCESS, 403);
        $this->selectedStudentId = $studentId;
        $this->selectedEvidence = $payment->evidence;
        $this->reset('paymentStatus', 'invalidReason');
        $this->resetValidation();
        unset($this->selectedPayment);
        Flux::modal('verify-payment')->show();
    }

    protected function rules(): array
    {
        return [
            'paymentStatus' => ['required', Rule::in([VerificationStatusEnum::VALID, VerificationStatusEnum::INVALID])],
            'invalidReason' => [Rule::requiredIf($this->paymentStatus === VerificationStatusEnum::INVALID), 'nullable', 'string', 'max:500'],
        ];
    }

    protected function messages(): array
    {
        return [
            'paymentStatus.required' => 'Pilih hasil verifikasi pembayaran.',
            'paymentStatus.in' => 'Pilih status Valid atau Tidak Valid.',
            'invalidReason.required' => 'Alasan penolakan wajib diisi.',
            'invalidReason.max' => 'Alasan penolakan maksimal 500 karakter.',
        ];
    }

    public function verifyPayment(RegistrationPaymentService $paymentService): void
    {
        $this->authorizeAdmin();
        abort_unless($this->selectedStudentId !== null && $this->selectedEvidence !== null, 403);
        RegistrationPaymentQuery::fetchPaymentForVerification($this->selectedStudentId, $this->selectedAdmissionId);
        $this->validate();

        try {
            $paymentService->verifyEvidence($this->selectedStudentId, $this->paymentStatus, $this->selectedEvidence, $this->invalidReason);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);
            session()->flash('verify-payment-failed', 'Verifikasi gagal disimpan. Silakan coba lagi.');

            return;
        }

        $this->reset('selectedStudentId', 'selectedEvidence', 'paymentStatus', 'invalidReason');
        unset($this->selectedPayment, $this->processStudentLists, $this->totalProcessStudent);
        Flux::modal('verify-payment')->close();
        $this->dispatch('toast', type: 'success', message: 'Status pembayaran berhasil diperbarui.');
    }

    protected function authorizeAdmin(): void
    {
        abort_unless(session('userCheck') && (int) auth()->user()?->role_id === RoleEnum::ADMIN, 403);
    }

    public function render(): View
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

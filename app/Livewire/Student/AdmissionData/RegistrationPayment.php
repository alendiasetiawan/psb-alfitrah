<?php

namespace App\Livewire\Student\AdmissionData;

use App\Enums\RoleEnum;
use App\Models\AdmissionData\Student;
use App\Queries\Payment\RegistrationPaymentQuery;
use App\Services\PaymentAccountService;
use App\Services\RegistrationPaymentService;
use App\Services\StudentDataService;
use Detection\MobileDetect;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Throwable;

#[Title('Biaya Pendaftaran')]
class RegistrationPayment extends Component
{
    use WithFileUploads;

    public bool $isMobile = false;

    #[Locked]
    public int $studentId;

    public ?TemporaryUploadedFile $evidence = null;

    protected function rules(): array
    {
        return ['evidence' => ['required', 'image', 'mimes:jpg,jpeg,png', 'extensions:jpg,jpeg,png', 'max:5120']];
    }

    protected function messages(): array
    {
        return [
            'evidence.required' => 'Bukti transfer wajib diunggah.',
            'evidence.image' => 'Bukti transfer harus berupa gambar.',
            'evidence.mimes' => 'Bukti transfer harus berformat JPG, JPEG, atau PNG.',
            'evidence.extensions' => 'Bukti transfer harus berformat JPG, JPEG, atau PNG.',
            'evidence.max' => 'Ukuran bukti transfer maksimal 5 MB.',
        ];
    }

    #[Computed]
    public function paymentAccount(): array
    {
        $this->authorizeStudent();

        return app(PaymentAccountService::class)->registrationAccount();
    }

    #[Computed]
    public function detailPayment(): Student
    {
        $this->authorizeStudent();

        return RegistrationPaymentQuery::fetchStudentPaymentDetails($this->studentId, (int) auth()->id());
    }

    public function mount(MobileDetect $mobileDetect, StudentDataService $studentDataService): void
    {
        $this->authorizeStudent();
        $this->isMobile = $mobileDetect->isMobile();
        $parent = auth()->user()->parent;
        abort_unless($parent, 403);
        $this->studentId = $studentDataService->findActiveStudentId($parent->id);
    }

    public function updatedEvidence(): void
    {
        $this->validateOnly('evidence');
    }

    public function saveEvidence(RegistrationPaymentService $paymentService): void
    {
        $this->authorizeStudent();
        $this->validate();

        try {
            $paymentService->submitEvidence($this->studentId, (int) auth()->id(), $this->evidence);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            report($exception);
            session()->flash('upload-payment-failed', 'Bukti transfer gagal disimpan. Silakan coba lagi.');

            return;
        }

        $this->reset('evidence');
        unset($this->detailPayment);
        session()->flash('upload-payment-success', 'Bukti transfer berhasil diunggah dan menunggu verifikasi admin.');
    }

    protected function authorizeStudent(): void
    {
        abort_unless(session('userCheck') && (int) auth()->user()?->role_id === RoleEnum::STUDENT, 403);
    }

    public function render(): View
    {
        if ($this->isMobile) {
            return view('livewire.mobile.student.admission-data.registration-payment')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => true,
            ]);
        }

        return view('livewire.web.student.admission-data.registration-payment')->layout('components.layouts.web.web-app');
    }
}

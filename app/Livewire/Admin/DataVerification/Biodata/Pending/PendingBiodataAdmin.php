<?php

namespace App\Livewire\Admin\DataVerification\Biodata\Pending;

use App\Enums\FollowUpStatusEnum;
use App\Helpers\AdmissionHelper;
use App\Helpers\MessageHelper;
use App\Helpers\WhaCenterHelper;
use App\Models\AdmissionData\AdmissionVerification;
use App\Queries\AdmissionData\AdmissionVerificationQuery;
use App\Queries\AdmissionData\StudentQuery;
use App\Queries\Payment\RegistrationPaymentQuery;
use App\Services\StudentDataService;
use Detection\MobileDetect;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Belum Mengisi Biodata')]
class PendingBiodataAdmin extends Component
{
    use WithPagination;

    public bool $isMobile = false;
    public object $admissionYearLists;
    public string $searchStudent = '';
    public ?int $selectedAdmissionId = null, $limitData = 9;

    protected StudentDataService $studentDataService;

    //ANCHOR: ACTION LOAD MORE
    #[On('load-more')]
    public function loadMore($loadItem)
    {
        $this->limitData += $loadItem;
    }

    #[Computed]
    public function pendingBiodataStudents()
    {
        return $this->studentDataService->paginateStudentPendingBiodata($this->selectedAdmissionId, $this->searchStudent, $this->limitData);
    }

    #[Computed]
    public function totalPendingBiodata()
    {
        return AdmissionVerificationQuery::countStudentPendingBiodata($this->selectedAdmissionId);
    }

    public function boot(MobileDetect $mobileDetect, StudentDataService $studentDataService)
    {
        $this->isMobile = $mobileDetect->isMobile();
        $this->studentDataService = $studentDataService;
    }

    public function mount()
    {
        $queryAdmission = AdmissionHelper::activeAdmission();
        $this->selectedAdmissionId = $queryAdmission->id;
        $this->admissionYearLists = AdmissionHelper::getAdmissionYearLists();
    }

    //ANCHOR: ACTION FOLLOW UP PAYMENT
    public function fuBiodata($studentId)
    {
        $studentQuery = StudentQuery::fetchDetailRegistrant($studentId);
        $waNumber = $studentQuery->country_code . $studentQuery->mobile_phone;

        try {
            DB::transaction(function () use ($studentId, $studentQuery, $waNumber) {
                //Send WA Message
                $message = MessageHelper::waFollowUpBiodata($studentQuery->student_name, $studentQuery->branch_name, $studentQuery->program_name, $studentQuery->academic_year);

                WhaCenterHelper::sendText($waNumber, $message);

                //Update fu status
                AdmissionVerification::where('student_id', $studentId)->update([
                    'fu_biodata' => FollowUpStatusEnum::DONE,
                ]);
            });

            $this->dispatch('toast', type: 'success', message: 'Pesan follow up berhasil dikirim');
            $this->pendingBiodataStudents();
        } catch (\Throwable $th) {
            session()->flash('error-fu-biodata', 'Upss.. Pengiriman pesan gagal, silahkan coba lagi nanti!');
        }
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.data-verification.biodata.pending.pending-biodata-admin')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => true,
            ]);
        }

        return view('livewire.web.admin.data-verification.biodata.pending.pending-biodata-admin')->layout('components.layouts.web.web-app');
    }
}

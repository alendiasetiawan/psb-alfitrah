<?php

namespace App\Livewire\Admin\DataVerification\StudentAttachment\Verified;

use App\Helpers\AdmissionHelper;
use App\Queries\AdmissionData\AdmissionVerificationQuery;
use App\Queries\Core\AdmissionQuery;
use App\Services\StudentDataService;
use Detection\MobileDetect;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
#[Title('Selesai Verifikasi Berkas')]
class VerifiedStudentAttachmentAdmin extends Component
{
    use WithPagination;

    public bool $isMobile = false;
    public string $searchStudent = '', $admissionYear, $title, $link;
    public ?int $selectedAdmissionId = null, $limitData = 10, $setCount = 1, $filterAdmissionId;
    public object $admissionYearLists;

    protected StudentDataService $studentDataService;

    //ANCHOR: ACTION LOAD MORE
    #[On('load-more')]
    public function loadMore($loadItem)
    {
        $this->limitData += $loadItem;
    }

    #[Computed]
    public function verifiedStudentAttachments()
    {
        return $this->studentDataService->paginateStudentVerifiedAttachment($this->selectedAdmissionId, $this->searchStudent, $this->limitData);
    }

    #[Computed]
    public function totalVerifiedAttachmentStudent()
    {
        return AdmissionVerificationQuery::countStudentVerifiedAttachment($this->selectedAdmissionId);
    }

    //ANCHOR: Boot
    public function boot(MobileDetect $mobileDetect, StudentDataService $studentDataService)
    {
        $this->isMobile = $mobileDetect->isMobile();
        $this->studentDataService = $studentDataService;
    }

    //ANCHOR: Mount
    public function mount()
    {
        $queryAdmission = AdmissionHelper::activeAdmission();
        $this->selectedAdmissionId = $queryAdmission->id;
        $this->admissionYearLists = AdmissionHelper::getAdmissionYearLists();
        $this->setAdmissionYear();
        $this->title = 'Selesai Verifikasi Berkas';
        $this->link = 'admin.dashboard';
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
        $this->setAdmissionYear();
    }

    public function updated($property) 
    {
        if ($property == 'searchStudent') {
            $this->setCount = 1;
        }
    }

    //ANCHOR: Action to set admission year
    public function setAdmissionYear()
    {
        $this->admissionYear = AdmissionQuery::fetchAdmissionDetail($this->selectedAdmissionId)->name;
    }

    //ANCHOR: Action to show student data
    public function showStudentAttachment($studentId)
    {
        $this->redirectRoute('admin.data_verification.student_attachment.verified.detail', ['studentId' => $studentId], navigate: true);
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.data-verification.student-attachment.verified.verified-student-attachment-admin')->layout('components.layouts.mobile.mobile-app', [
                'isFixedTop' => true,
            ]);
        }
        return view('livewire.web.admin.data-verification.student-attachment.verified.verified-student-attachment-admin')->layout('components.layouts.web.web-app');
    }
}

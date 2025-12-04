<?php

namespace App\Livewire\Admin\MasterData;

use App\Helpers\AdmissionHelper;
use App\Helpers\CacheKeys\Admin\AdminMasterDataKey;
use App\Models\AdmissionData\Student;
use App\Models\User;
use App\Queries\AdmissionData\StudentQuery;
use App\Services\StudentDataService;
use Detection\MobileDetect;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Database Pendaftar')]
class RegistrantDatabase extends Component
{
    use WithPagination;

    public bool $isMobile = false;
    public string $searchStudent = '';
    public ?int $selectedAdmissionId = null, $limitData = 15, $totalStudent;
    public object $admissionYearLists;

    protected AdmissionHelper $admissionHelper;
    protected StudentDataService $studentDataService;

    #[Computed]
    public function registrantLists()
    {
        return Cache::remember(
            AdminMasterDataKey::adminMasterStudentRegistrant(),
            604800,
            fn() =>
            StudentQuery::paginateStudentRegistrant(
                searchStudent: $this->searchStudent,
                selectedAdmissionId: $this->selectedAdmissionId,
                limitData: $this->limitData
            )
        );
    }

    public function boot(MobileDetect $mobileDetect, AdmissionHelper $admissionHelper, StudentDataService $studentDataService)
    {
        $this->isMobile = $mobileDetect->isMobile();
        $this->admissionHelper = $admissionHelper;
        $this->studentDataService = $studentDataService;
    }

    public function mount()
    {
        $queryAdmission = $this->admissionHelper::activeAdmission();
        $this->selectedAdmissionId = $queryAdmission->id;
        $this->admissionYearLists = AdmissionHelper::getAdmissionYearLists();

        //Set cache total student
        $this->totalStudent = Cache::remember(
            AdminMasterDataKey::adminMasterTotalRegistrant(),
            604800,
            fn() => StudentQuery::countStudentRegistrant($this->selectedAdmissionId)
        );
    }

    public function updated()
    {
        $this->resetPage();
        Cache::forget(AdminMasterDataKey::adminMasterStudentRegistrant());
    }

    //ANCHOR - Action Delete Student
    public function deleteStudent($id)
    {
        try {
            $realId = Crypt::decrypt($id);
            $studentQuery = Student::find($realId);
            $userId = $studentQuery->user_id;

            //Do not delete user data if the user has more than one student
            $isMultiStudent = $this->studentDataService->isMultiStudent($realId);

            DB::transaction(function () use ($studentQuery, $userId, $isMultiStudent) {
                if (!$isMultiStudent) {
                    $user = User::find($userId);
                    $user->delete();
                } else {
                    $studentQuery->delete();
                }
            });

            $this->dispatch('toast', type: 'warning', message: 'Data berhasil dihapus!');
            $this->redirect(route('admin.master_data.registrant_database'), navigate: true);
        } catch (\Throwable $th) {
            session()->flash('error-delete-student', 'Gagal menghapus data, silahkan coba lagi!');
        }
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.master-data.registrant-database')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => true,
            ]);
        }
        return view('livewire.web.admin.master-data.registrant-database')->layout('components.layouts.web.web-app');
    }
}

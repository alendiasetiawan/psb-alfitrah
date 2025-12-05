<?php

namespace App\Livewire\Admin\MasterData\StudentDatabase;

use App\Helpers\AdmissionHelper;
use App\Models\AdmissionData\Student;
use App\Models\User;
use App\Queries\AdmissionData\StudentQuery;
use App\Queries\Core\BranchQuery;
use App\Services\StudentDataService;
use Detection\MobileDetect;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class IndexStudentDatabase extends Component
{
    use WithPagination;

    public bool $isMobile = false;
    public string $searchStudent = '', $walkoutReason = '';
    public ?int $selectedAdmissionId = null, $limitData = 10, $setCount = 1;
    public object $admissionYearLists;

    protected AdmissionHelper $admissionHelper;
    protected StudentDataService $studentDataService;

    #[Computed]
    public function officialStudentLists()
    {
        return StudentQuery::paginateOfficialStudent(
            searchStudent: $this->searchStudent,
            selectedAdmissionId: $this->selectedAdmissionId,
            limitData: $this->limitData
        );
    }

    #[Computed]
    public function totalStudents()
    {
        return BranchQuery::counterStudentOfficial($this->selectedAdmissionId);
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
    }

    //ANCHOR - HANDLE NUMBER ON PAGE UPDATE
    public function updatedPage($page)
    {
        $setPage = $page - 1;
        $dataLoaded = $setPage * $this->limitData;
        $this->setCount = $dataLoaded + 1;
    }

    //ANCHOR - DELETE STUDENT
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
            $this->officialStudentLists();
            $this->totalStudents();
        } catch (\Throwable $th) {
            session()->flash('error-delete-student', 'Gagal menghapus data, silahkan coba lagi!');
        }
    }

    //ANCHOR - SET WALKOUT STUDENT
    public function walkoutStudent($id)
    {
        $this->validate([
            'walkoutReason' => 'required',
        ], [
            'walkoutReason.required' => 'Alasan tidak boleh kosong!',
        ]);

        try {
            $realId = Crypt::decrypt($id);

            DB::transaction(function () use ($realId) {
                //Update walkout status
                DB::table('students')->where('id', $realId)->update([
                    'is_walkout' => true,
                ]);

                //Fill walkout reason
                DB::table('walkout_students')->insert([
                    'student_id' => $realId,
                    'reason' => $this->walkoutReason,
                ]);
            });

            $this->dispatch('toast', type: 'success', message: 'Data berhasil disimpan!');
            $this->officialStudentLists();
            $this->totalStudents();
        } catch (\Throwable $th) {
            session()->flash('error-set-walkout-student', 'Gagal menyimpan data, silahkan coba lagi beberapa saat lagi!');
        }
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.master-data.student-database.index-student-database')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => true,
            ]);
        }

        return view('livewire.web.admin.master-data.student-database.index-student-database')->layout('components.layouts.web.web-app');
    }
}

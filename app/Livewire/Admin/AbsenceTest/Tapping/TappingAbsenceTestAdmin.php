<?php

namespace App\Livewire\Admin\AbsenceTest\Tapping;

use App\Helpers\AdmissionHelper;
use App\Models\PlacementTest\PlacementTestPresence;
use App\Models\PlacementTest\PlacementTestResult;
use App\Models\PlacementTest\TestQrCode;
use App\Queries\PlacementTest\PlacementTestPresenceQuery;
use App\Queries\PlacementTest\TestQrCodeQuery;
use Detection\MobileDetect;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Tapping QR')]
class TappingAbsenceTestAdmin extends Component
{
    use WithPagination;

    public bool $isMobile = false;
    public ?int $limitData = 10, $setCount = 1, $admissionId = null;
    public string $studentQr = '', $academicYear = '';

    //ANCHOR: ACTION LOAD MORE
    #[On('load-more')]
    public function loadMore($loadItem)
    {
        $this->limitData += $loadItem;
    }

    #[Computed]
    public function presenceStudents()
    {
        return PlacementTestPresenceQuery::paginatePresenceStudents($this->admissionId, $this->limitData);
    }

    //ANCHOR: Boot hook
    public function boot(MobileDetect $mobileDetect)
    {
        $this->isMobile = $mobileDetect->isMobile();
    }

    //ANCHOR: Mount hook
    public function mount()
    {
        $queryAdmission = AdmissionHelper::activeAdmission();
        $this->admissionId = $queryAdmission->id;
        $this->academicYear = $queryAdmission->name;
    }

    //ANCHOR: Execute when page number is updated
    public function updatedPage($page)
    {
        $setPage = $page - 1;
        $dataLoaded = $setPage * $this->limitData;
        $this->setCount = $dataLoaded + 1;
    }

    //ANCHOR: Tapping QR
    public function scanQr()
    {
        try {
            //Check if the code is valid
            $isQrValid = TestQrCodeQuery::validQrQuery($this->studentQr);

            if (!$isQrValid) {
               $this->dispatch('toast', type: 'error', message: 'QR Code tidak valid');
            } else {
                //Fetch student valid student_id
                $queryQr = TestQrCode::where('qr', $this->studentQr)->first();
                $studentId = $queryQr->student_id;

                //Check if student has tapped
                $isPresence = PlacementTestPresence::where('student_id', $studentId)->exists();

                if ($isPresence) {
                    return $this->dispatch('toast', type: 'warning', message: 'Siswa sudah absen');
                } else {
                    DB::transaction(function () use($studentId) {
                        //Save presence
                        PlacementTestPresence::create([
                            'student_id' => $studentId,
                            'check_in_time' => now(),
                        ]);

                        //Save for placement test results data
                        PlacementTestResult::create([
                            'student_id' => $studentId
                        ]);
                    });

                    $this->reset('studentQr');
                    $this->presenceStudents();
                    $this->dispatch('toast', type: 'success', message: 'Tapping berhasil!');
                }
            }
        } catch (\Throwable $th) {
            logger($th);
            return session()->flash('error-tapping', 'Tapping gagal, coba lagi!');
        }
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.absence-test.tapping.tapping-absence-test-admin')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => true
            ]);
        }

        return view('livewire.web.admin.absence-test.tapping.tapping-absence-test-admin')->layout('components.layouts.web.web-app');
    }
}

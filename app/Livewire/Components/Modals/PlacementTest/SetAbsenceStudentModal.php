<?php

namespace App\Livewire\Components\Modals\PlacementTest;

use App\Models\PlacementTest\PlacementTestPresence;
use App\Models\PlacementTest\PlacementTestResult;
use App\Queries\PlacementTest\PlacementTestPresenceQuery;
use Detection\MobileDetect;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class SetAbsenceStudentModal extends Component
{
    public string $modalId = '';
    public array $inputs = [
        'presence' => '',
        'presenceTime' => ''
    ];
    public object $studentQuery;
    public bool $isMobile = false;

    #[On('filled-absence-student-modal')]
    public function setEditValue($id) {
        $realId = Crypt::decrypt($id);
        $this->studentQuery = PlacementTestPresenceQuery::detailStudentPresence($realId);

        //Set value base on presence table
        if (!empty($this->studentQuery->placementTestPresence)) {
            $this->inputs['presence'] = "Hadir";
            $this->inputs['presenceTime'] = $this->studentQuery->placementTestPresence->check_in_time;
        } else {
            $this->inputs['presence'] = "Tidak Hadir";
            $this->inputs['presenceTime'] = '';
        }   
    }


    //ANCHOR: Mount hook
    public function boot(MobileDetect $mobileDetect) 
    {
        $this->isMobile = $mobileDetect->isMobile();
    }

    //ANCHOR - Reset property when modal is closed
    public function resetAllProperty() {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->reset();
        $this->dispatch('reset-submit');
    }

    //ANCHOR: ACTION SAVE PRESENCE DATA
    public function saveAbsenceStudent() {
        //Validate inputs
        $this->validate([
            'inputs.presence' => 'required',
            'inputs.presenceTime' => 'required'
        ] , [
            'inputs.presence.required' => 'Silahkan pilih status kehadiran',
            'inputs.presenceTime.required' => 'Waktu kehadiran harus diisi'
        ]);

        try {
            //Query base on inputs presence condition
            DB::transaction(function () {
                if ($this->inputs['presence'] == 'Hadir') {
                    //Save presence data
                    PlacementTestPresence::updateOrCreate([
                        'student_id' => $this->studentQuery->id,
                    ], [
                        'check_in_time' => $this->inputs['presenceTime']
                    ]);

                    //Save test result data
                    PlacementTestResult::updateOrCreate([
                        'student_id' => $this->studentQuery->id
                    ], [
                        'updated_at' => now()
                    ]);
                } else {
                    //Delete presence data
                    PlacementTestPresence::where('student_id', $this->studentQuery->id)->delete();
                    //Delete test result data
                    PlacementTestResult::where('student_id', $this->studentQuery->id)->delete();
                }
            });

            $this->dispatch('toast', type: 'success', message:  'Data absensi berhasil disimpan!');
        } catch (\Throwable $th) {
            logger($th);
            session()->flash('save-failed', 'Gagal menyimpan data absensi, silahkan coba lagi!');
        }
    }

    public function render()
    {
        return view('livewire.components.modals.placement-test.set-absence-student-modal');
    }
}

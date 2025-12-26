<?php

namespace App\Livewire\Components\Modals\PlacementTest;

use App\Helpers\MessageHelper;
use App\Helpers\WhaCenterHelper;
use App\Models\PlacementTest\PlacementTestResult;
use App\Queries\PlacementTest\PlacementTestResultQuery;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\On;
use Livewire\Component;

class SetPublicationTestResultModal extends Component
{
    public string $modalId, $studentName, $encryptedTestId;
    public array $inputs = [
        'publicationStatus' => '',
        'isNotificationSent' => false
    ];
    public int $studentId;
    public bool $isMobile;

    #[On('open-publication-modal')]
    public function setEditValue($id)
    {
        try {
            $realId = Crypt::decrypt($id);
            $this->encryptedTestId = $id;
            $query = PlacementTestResultQuery::fetchStudentPublicationStatus($realId);
            $this->studentName = $query->student_name;
            $this->inputs['publicationStatus'] = $query->publication_status;
            $this->studentId = $query->student_id;
        } catch (\Throwable $th) {
            logger($th);
            session()->flash('error-execute-modal', 'Upss.. terjadi kesalahan, silahkan coba lagi!');
        }
    }

    //ANCHOR: Action save publication status
    public function savePublication()
    {
        try {
            $realId = Crypt::decrypt($this->encryptedTestId);
            PlacementTestResult::where('id', $realId)->update([
                'publication_status' => $this->inputs['publicationStatus'],
                'publication_date' => now()
            ]);

            $this->dispatch('toast', type: 'success', message: 'Status publikasi berhasil diupdate!');
        } catch (\Throwable $th) {
            logger($th);
            return session()->flash('failed-update-publication', 'Upss.. terjadi kesalahan, silahkan coba lagi!');
        }

        //Send Notification
        if ($this->inputs['isNotificationSent'] == true) {
            //Fetch student data
            $query = PlacementTestResultQuery::fetchStudentDetailTest($this->studentId);
            $branchName = $query->branch_name;
            $programName = $query->program_name;
            $academicYear = $query->academic_year;
            $finalResult = $query->final_result;
            $finalScore = $query->final_score;
            $mobilePhone = $query->country_code . $query->mobile_phone;

            $message = MessageHelper::waReleaseTestResult($this->studentName, $branchName, $programName, $academicYear, $finalResult, $finalScore);

            $sendMessage = WhaCenterHelper::sendText($mobilePhone, $message);

            $responseTest = json_decode($sendMessage, true);
            if ($responseTest['status'] == false) {
                $this->dispatch('notification-failed');
            }
        }
    }

    public function render()
    {
        return view('livewire.components.modals.placement-test.set-publication-test-result-modal');
    }
}

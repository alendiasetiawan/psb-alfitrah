<?php

namespace App\Livewire\Components\Modals\PlacementTest;

use App\Enums\PlacementTestEnum;
use App\Helpers\MessageHelper;
use App\Helpers\WhaCenterHelper;
use App\Models\PlacementTest\PlacementTestResult;
use App\Queries\PlacementTest\PlacementTestResultQuery;
use Livewire\Attributes\On;
use Livewire\Component;

class ConfirmReleaseModal extends Component
{
    public string $modalId;
    public object $holdStudents;
    public array $failedNotifications = [];
    public int $totalHoldStudents;
    public bool $isMobile;

    #[On('open-confirm-release-modal')]
    public function setValue() 
    {
        $this->setHoldStudents();
        $this->totalHoldStudents = $this->holdStudents->count();
    }

    public function setHoldStudents()
    {
        $this->holdStudents = PlacementTestResultQuery::getHoldStudents();
    }

    public function releaseTestResult()
    {
        $this->setHoldStudents();

        try {
            PlacementTestResult::where('publication_status', PlacementTestEnum::PUBLICATION_HOLD)
            ->where('final_result', '!=', PlacementTestEnum::RESULT_WAITING)
            ->update([
                'publication_status' => PlacementTestEnum::PUBLICATION_RELEASE
            ]);

            // Send schedule message for WA Notification
            foreach ($this->holdStudents as $index => $student) {
                $timeSchedule = now()->addMinutes(1)->addSeconds($index * 10);
                $waNumber = $student->country_code . $student->mobile_phone;
                $message = MessageHelper::waReleaseTestResult($student->student_name, $student->branch_name, $student->program_name, $student->academic_year, $student->final_result, $student->final_score);

                $sendMessage = WhaCenterHelper::sendScheduleText($waNumber, $message, $timeSchedule);
                $response = json_decode($sendMessage, true);

                if ($response['status'] == false) {
                    $this->failedNotifications[] = $student->student_name;
                }
            }

            if (count($this->failedNotifications) > 0) {
                session()->flash('failed-sent-notification', 'Gagal mengirimkan notifikasi, cek aplikasi Whatsapp anda!');
            }

            $this->dispatch('toast', type: 'success', message: 'Berhasil release hasil tes');
        } catch (\Throwable $th) {
            logger($th);
            session()->flash('error-release', 'Gagal release hasil tes, silakan coba lagi!');
        }
    }

    public function render()
    {
        return view('livewire.components.modals.placement-test.confirm-release-modal');
    }
}

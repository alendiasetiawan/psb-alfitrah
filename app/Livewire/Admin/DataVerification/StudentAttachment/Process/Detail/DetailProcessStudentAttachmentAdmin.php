<?php

namespace App\Livewire\Admin\DataVerification\StudentAttachment\Process\Detail;

use App\Enums\VerificationStatusEnum;
use App\Helpers\MessageHelper;
use App\Helpers\WhaCenterHelper;
use App\Models\AdmissionData\AdmissionVerification;
use App\Models\AdmissionData\StudentAttachment;
use App\Queries\AdmissionData\StudentQuery;
use App\Traits\FlushStudentAdmissionDataTrait;
use Carbon\Carbon;
use Detection\MobileDetect;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detail Berkas Siswa')]
class DetailProcessStudentAttachmentAdmin extends Component
{
    use FlushStudentAdmissionDataTrait;

    #[Locked]
    public $decryptedStudentId;
    public bool $isMobile = false;
    public string $studentId = '', $attachmentStatus = '', $studentName = '', $branchName = '', $programName = '', $academicYear = '';
    public object $attachmentDetail;
    public array $inputs = [
        'photoStatus' => '',
        'bornCardStatus' => '',
        'familyCardStatus' => '',
        'parentCardStatus' => '',
        'invalidReason' => ''
    ];

    //ANCHOR: Boot execuite every time component rendered
    public function boot(MobileDetect $mobileDetect)
    {
        $this->isMobile = $mobileDetect->isMobile();
    }

    //ANCHOR: Mount execute once
    public function mount()
    {
        try {
            $realId = Crypt::decrypt($this->studentId);
            $this->decryptedStudentId = $realId;
            $this->attachmentDetail = StudentQuery::fetchStudentAttachmentWithStatus($this->decryptedStudentId);

            //Set predefine value
            $this->inputs['photoStatus'] = $this->attachmentDetail->studentAttachment->photo_status != VerificationStatusEnum::PROCESS ? $this->attachmentDetail->studentAttachment->photo_status : '';
            $this->inputs['bornCardStatus'] = $this->attachmentDetail->studentAttachment->born_card_status != VerificationStatusEnum::PROCESS ? $this->attachmentDetail->studentAttachment->born_card_status : '';
            $this->inputs['familyCardStatus'] = $this->attachmentDetail->studentAttachment->family_card_status != VerificationStatusEnum::PROCESS ? $this->attachmentDetail->studentAttachment->family_card_status : '';
            $this->inputs['parentCardStatus'] = $this->attachmentDetail->studentAttachment->parent_card_status != VerificationStatusEnum::PROCESS ? $this->attachmentDetail->studentAttachment->parent_card_status : '';
            $this->inputs['invalidReason'] = $this->attachmentDetail->attachment_error_msg;

            $this->studentName = $this->attachmentDetail->student_name;
            $this->branchName = $this->attachmentDetail->branch_name;
            $this->programName = $this->attachmentDetail->program_name;
            $this->academicYear = $this->attachmentDetail->academic_year;
        } catch (\Throwable $th) {
            logger($th);
            session()->flash('error-fetch-student-detail', 'Ups.. terjadi kesalahan, silahkan coba lagi nanti!');
        }
    }


    //ANCHOR: Save status biodata
    public function updateAttachmentStatus()
    {
        //Validate if status is Invalid
        if ($this->inputs['photoStatus'] == VerificationStatusEnum::INVALID || $this->inputs['bornCardStatus'] == VerificationStatusEnum::INVALID || $this->inputs['familyCardStatus'] == VerificationStatusEnum::INVALID || $this->inputs['parentCardStatus'] == VerificationStatusEnum::INVALID) {
            $this->validate([
                'inputs.invalidReason' => 'required'
            ], [
                'inputs.invalidReason.required' => 'Alasan tidak boleh kosong'
            ]);
            
            $this->attachmentStatus = VerificationStatusEnum::INVALID;
        } else {
            $this->attachmentStatus = VerificationStatusEnum::VALID;
        }


        //Query to database
        try {
            DB::transaction(function () {
                //Update verification status
                $admission = AdmissionVerification::where('student_id', $this->decryptedStudentId)->first();
                $admission->update([
                    'attachment' => $this->attachmentStatus,
                    'attachment_error_msg' => $this->inputs['invalidReason'],
                    'attachment_verified_at' => Carbon::now()
                ]);

                //Update attachment status
                $attachment = StudentAttachment::where('student_id', $this->decryptedStudentId)->first();
                $attachment->update([
                    'photo_status' => $this->inputs['photoStatus'],
                    'born_card_status' => $this->inputs['bornCardStatus'],
                    'family_card_status' => $this->inputs['familyCardStatus'],
                    'parent_card_status' => $this->inputs['parentCardStatus'],
                ]);
            });

            $this->flushAttachment($this->decryptedStudentId);
            $this->dispatch('toast', type: 'success', message: 'Status berkas berhasil diubah');
            $this->redirect(route('admin.data_verification.student_attachment.process'), navigate: true);
        } catch (\Throwable $th) {
            logger($th);
            session()->flash('error-update-attachment', 'Ups.. terjadi kesalahan, silahkan coba lagi nanti!');
        }

        //Send message when attachment is Invalid
        if ($this->attachmentStatus == VerificationStatusEnum::INVALID) {
            $studentPhone = $this->attachmentDetail->country_code . $this->attachmentDetail->mobile_phone;

            try {
                $message = MessageHelper::waInvalidAttachment($this->studentName, $this->branchName, $this->programName, $this->academicYear, $this->inputs['invalidReason']);
                WhaCenterHelper::sendText($studentPhone, $message);
            } catch (\Throwable $th) {
                logger($th);
                session()->flash('notification-failed', 'Notifikasi WA gagal dikirim, silahkan follow up secara manual');
            }
        }
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.data-verification.student-attachment.process.detail.detail-process-student-attachment-admin')->layout('components.layouts.mobile.mobile-app', [
                'isShowBackButton' => true,
                'isRollOver' => true
            ]);
        }

        return view('livewire.web.admin.data-verification.student-attachment.process.detail.detail-process-student-attachment-admin')->layout('components.layouts.web.web-app');
    }
}

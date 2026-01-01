<?php

namespace App\Livewire\Admin\TestResult\Detail;

use App\Models\PlacementTest\PlacementTestResult;
use App\Models\PlacementTest\Tester;
use App\Models\PlacementTest\TestPassScore;
use App\Queries\PlacementTest\PlacementTestResultQuery;
use App\Validation\PlacementTestResultValidation;
use Detection\MobileDetect;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Throwable;

#[Title('Edit Nilai Tes')]
class DetailTestResultAdmin extends Component
{
    public bool $isMobile;
    public string $studentId, $encryptedTestId;
    public object $detailTest;
    public $testerLists = [], $minFinalScore, $psikotestWeight, $readQuranWeight;
    public array $inputs = [
        'psikotestScore' => '',
        'readQuranScore' => '',
        'selectedReadQuranTester' => '',
        'parentInterview' => '',
        'selectedParentInterviewTester' => '',
        'studentInterview' => '',
        'selectedStudentInterviewTester' => '',
        'finalScore' => '',
        'finalResult' => '',
        'finalNote' => '',
        'publicationStatus' => '',
    ];

    protected function rules(): array
    {
        return PlacementTestResultValidation::create();
    }

    protected function messages(): array
    {
        return PlacementTestResultValidation::messages();
    }

    #[On('tester-saved')]
    public function fetchTester()
    {
        $this->getTesterLists();
    }

    public function mount($studentId)
    {
        try {
            $realId = Crypt::decrypt($studentId);
            $this->detailTest = PlacementTestResultQuery::fetchStudentDetailTest($realId);

            $this->encryptedTestId = Crypt::encrypt($this->detailTest->test_id);

            //Set predefined value
            $this->inputs['psikotestScore'] = $this->detailTest->psikotest_score ?? '';
            $this->inputs['readQuranScore'] = $this->detailTest->read_quran_score ?? '';
            $this->inputs['selectedReadQuranTester'] = $this->detailTest->read_quran_tester ?? '';
            $this->inputs['parentInterview'] = $this->detailTest->parent_interview ?? ''; 
            $this->inputs['selectedParentInterviewTester'] = $this->detailTest->parent_interview_tester ?? '';
            $this->inputs['studentInterview'] = $this->detailTest->student_interview ?? '';
            $this->inputs['selectedStudentInterviewTester'] = $this->detailTest->student_interview_tester ?? '';
            $this->inputs['finalResult'] = $this->detailTest->final_result ?? '';
            $this->inputs['finalScore'] = $this->detailTest->final_score ?? '';
            $this->inputs['finalNote'] = $this->detailTest->final_note ?? '';

            //Get pass score
            $passScore = TestPassScore::first();
            $this->minFinalScore = $passScore->min_final_score;
            $this->psikotestWeight = $passScore->psikotest_weight;
            $this->readQuranWeight = $passScore->read_quran_weight;
        } catch (Throwable $th) {
            session()->flash('error-fetch-data', 'Upss.. Gagal menampilkan data, dilarang modifikasi ID');
        }

        $this->getTesterLists();
    }

    //ANCHOR: Boot hook
    public function boot(MobileDetect $mobileDetect)
    {
        $this->isMobile = $mobileDetect->isMobile();
    }

    public function getTesterLists() 
    {
        $this->testerLists = Tester::orderBy('name', 'asc')->pluck('name', 'id');
    }

    //ANCHOR: ACTION SAVE DATA
    public function saveScore()
    {
        $this->validate();

        try {
            $decryptedTestId = Crypt::decrypt($this->encryptedTestId);

            PlacementTestResult::where('id', $decryptedTestId)->update([
                'psikotest_score' => $this->inputs['psikotestScore'],
                'read_quran_score' => $this->inputs['readQuranScore'],
                'read_quran_tester' => $this->inputs['selectedReadQuranTester'],
                'parent_interview' => $this->inputs['parentInterview'],
                'parent_interview_tester' => $this->inputs['selectedParentInterviewTester'],
                'student_interview' => $this->inputs['studentInterview'],
                'student_interview_tester' => $this->inputs['selectedStudentInterviewTester'],
                'final_result' => $this->inputs['finalResult'],
                'final_score' => $this->inputs['finalScore'],
                'final_note' => $this->inputs['finalNote'],
            ]);

            $this->redirectRoute('admin.placement_test.test_result', navigate: true);
            $this->dispatch('toast', type: 'success', message: 'Nilai berhasil disimpan');
        } catch (\Throwable $th) {
            logger($th);
            session()->flash('error-save-score', 'Upss.. Gagal menyimpan nilai. Silahkan coba beberapa saat lagi');
        }
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.test-result.detail.detail-test-result-admin')->layout('components.layouts.mobile.mobile-app', [
                'isShowBackButton' => true,
                'isShowTitle' => true,
            ]);
        }

        return view('livewire.web.admin.test-result.detail.detail-test-result-admin')->layout('components.layouts.web.web-app');
    }
}

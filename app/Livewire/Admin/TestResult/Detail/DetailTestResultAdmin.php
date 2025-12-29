<?php

namespace App\Livewire\Admin\TestResult\Detail;

use App\Models\PlacementTest\Tester;
use App\Models\PlacementTest\TestPassScore;
use App\Queries\PlacementTest\PlacementTestResultQuery;
use Detection\MobileDetect;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Throwable;

#[Title('Form Edit Nilai')]
class DetailTestResultAdmin extends Component
{
    public bool $isMobile;
    public string $studentId;
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

            //Set predefined value
            $this->inputs['finalResult'] = $this->detailTest->final_result;
            $this->inputs['finalScore'] = $this->detailTest->final_score;
            $this->inputs['finalNote'] = $this->detailTest->final_note;
            $this->inputs['publicationStatus'] = $this->detailTest->publication_status;

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
        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.test-result.detail.detail-test-result-admin')->layout('components.layouts.mobile.mobile-app', [
                'isShowBackButton' => true,
                'isShowTitle' => true
            ]);
        }

        return view('livewire.web.admin.test-result.detail.detail-test-result-admin')->layout('components.layouts.web.web-app');
    }
}

<?php

namespace App\Livewire\Admin;

use App\Helpers\AdmissionHelper;
use App\Queries\Core\AdminDashboardQuery;
use Asantibanez\LivewireCharts\Facades\LivewireCharts;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Asantibanez\LivewireCharts\Models\RadialChartModel;
use Detection\MobileDetect;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard Admin PSB')]
class AdminDashboard extends Component
{
    public bool $isMobile = false;
    public object $counterStatistic, $activeAdmission, $biodataStatus, $studentAttachmentStatus, $latestRegistrants;
    public int $admissionId;

    //PROPERTY: Value percentage payment success
    #[Computed]
    public function countPaymentSuccess()
    {
        return AdminDashboardQuery::comparePaymentSuccess($this->admissionId);
    }

    //PROPERTY: Set Registrant Per Program Statistic
    #[Computed]
    public function registrantPerProgram()
    {
        $dataChart = AdminDashboardQuery::chartRegistrantPerProgram($this->admissionId);

        $columnChartModel = $dataChart->reduce(
            function ($columnChartModel, $item) {
                $totalRegistrant = $item->students->count();
                $columnChartModel->addColumn($item->program_name, $totalRegistrant, sprintf('#%06X', mt_rand(0, 0xffffff)));
                return $columnChartModel;
            },
            LivewireCharts::columnChartModel()
                ->setTitle('Jumlah Siswa')
                ->setAnimated(true)
                ->withLegend()
                ->setDataLabelsEnabled(true)
                ->setJsonConfig([
                    'chart' => [
                        'foreColor' => '#FFFFFF',
                    ],
                    'legend' => [
                        'labels' => [
                            'colors' => '#FFFFFF',
                        ],
                    ],
                    'xaxis' => [
                        'labels' => [
                            'style' => [
                                'colors' => '#FFFFFF',
                            ],
                        ],
                    ],
                    'yaxis' => [
                        'labels' => [
                            'style' => [
                                'colors' => '#FFFFFF',
                            ],
                        ],
                    ],
                    'tooltip.y.formatter' => '(val) => `${val}`',
                    'yaxis.labels.formatter' => '(val) => `${val}`',
                ]),
        );

        return $columnChartModel;
    }

    //PROPERTY: Set Percentage Total Payment Success
    #[Computed]
    public function percentageTotalPaymentSuccess()
    {
        $totalRegistrant = $this->countPaymentSuccess->total_registrant;
        $totalPayment = $this->countPaymentSuccess->total_payment_success;
        $percentage = round(($totalPayment / $totalRegistrant) * 100, 2);

        $radialChartModel =
            (new RadialChartModel())
            ->showTotal()
            ->setAnimated(true)
            ->setJsonConfig([
                'chart' => [
                    'dropShadow' => [
                        'enabled' => true,
                        'top' => 3,
                        'left' => 2,
                        'blur' => 4,
                        'opacity' => 0.35,
                    ],
                    'foreColor' => '#FFFFFF',

                ],
            ])
            ->addBar('Total Pendaftar', $percentage, '#06ba21');

        return $radialChartModel;
    }

    //HOOK: BOOT
    public function boot(MobileDetect $mobileDetect, AdmissionHelper $admissionHelper)
    {
        $this->isMobile = $mobileDetect->isMobile();
        $this->activeAdmission = $admissionHelper->activeAdmission();
        $this->admissionId = $this->activeAdmission->id;
    }

    //HOOK: MOUNT
    public function mount()
    {
        $this->counterStatistic = AdminDashboardQuery::counterStatisticSummary($this->admissionId);
        $this->biodataStatus = AdminDashboardQuery::countBiodataStatus($this->admissionId);
        $this->studentAttachmentStatus = AdminDashboardQuery::countStudentAttachmentStatus($this->admissionId);
        $this->latestRegistrants = AdminDashboardQuery::latestRegistrant($this->admissionId);
    }

    public function render()
    {
        if ($this->isMobile) {
            return view('livewire.mobile.admin.admin-dashboard')->layout('components.layouts.mobile.mobile-app', [
                'isShowBottomNavbar' => true,
                'isShowTitle' => false,
            ]);
        }

        return view('livewire.web.admin.admin-dashboard')->layout('components.layouts.web.web-app');
    }
}

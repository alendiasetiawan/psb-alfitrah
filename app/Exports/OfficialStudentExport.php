<?php

namespace App\Exports;

use App\Queries\AdmissionData\StudentQuery;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;

class OfficialStudentExport extends \PhpOffice\PhpSpreadsheet\Cell\StringValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder, WithStyles
{
    public function __construct($branchId, $admissionId)
    {
        $this->branchId = $branchId;
        $this->admissionId = $admissionId;
    }

    public function view(): View
    {
        $data = [
            'studentLists' => StudentQuery::getDownloadStudentInBranch($this->branchId, $this->admissionId)
        ];

        return view('excels.admin.student-master-data', $data);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}

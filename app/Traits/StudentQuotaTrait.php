<?php

namespace App\Traits;

use App\Queries\AdmissionData\StudentQuery;
use App\Queries\Core\AdmissionQuotaQuery;

trait StudentQuotaTrait
{
    // Count how many student pass placement test
    public function countStudentPassPlacementTest($admissionId, $educationProgramId)
    {
        return StudentQuery::countStudentPassTest($admissionId, $educationProgramId);
    }

    // Get quota of selected admission and program
    public function programQuota($admissionId, $educationProgramId)
    {
        return AdmissionQuotaQuery::fetchQuotaAdmissionProgram($admissionId, $educationProgramId);
    }

    // Check if quota is available with early return if the program is closed
    public function isQuotaAvailable($admissionId, $educationProgramId)
    {
        $programQuota = $this->programQuota($admissionId, $educationProgramId);
        if (! $programQuota || $programQuota->status != 'Buka' || $programQuota->amount <= 0) {
            return false;
        }

        $totalStudent = $this->countStudentPassPlacementTest($admissionId, $educationProgramId);
        $quota = $programQuota->amount;
        if ($totalStudent >= $quota) {
            return false;
        }

        return true;
    }
}

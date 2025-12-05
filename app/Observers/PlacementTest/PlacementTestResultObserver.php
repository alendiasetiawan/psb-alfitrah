<?php

namespace App\Observers\PlacementTest;

use App\Models\PlacementTest\PlacementTestResult;
use App\Traits\FlushAdminMasterDataTrait;

class PlacementTestResultObserver
{
    use FlushAdminMasterDataTrait;

    public $afterCommit = true;
    /**
     * Handle the PlacementTestResult "created" event.
     */
    public function created(PlacementTestResult $placementTestResult): void
    {
        $this->flushStudentOfficial();
        $this->flushTotalStudentOfficialBranch();
    }

    /**
     * Handle the PlacementTestResult "updated" event.
     */
    public function updated(PlacementTestResult $placementTestResult): void
    {
        $this->flushStudentOfficial();
        $this->flushTotalStudentOfficialBranch();
    }

    /**
     * Handle the PlacementTestResult "deleted" event.
     */
    public function deleted(PlacementTestResult $placementTestResult): void
    {
        $this->flushStudentOfficial();
        $this->flushTotalStudentOfficialBranch();
    }
}

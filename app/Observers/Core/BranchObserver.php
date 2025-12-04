<?php

namespace App\Observers\Core;

use App\Models\Core\Branch;
use App\Traits\FlushAdminMasterDataTrait;

class BranchObserver
{
    use FlushAdminMasterDataTrait;
    public $afterCommit = true;
    /**
     * Handle the Branch "created" event.
     */
    public function created(Branch $branch): void
    {
        $this->flushRegistrant();
    }

    /**
     * Handle the Branch "updated" event.
     */
    public function updated(Branch $branch): void
    {
        $this->flushRegistrant();
    }

    /**
     * Handle the Branch "deleted" event.
     */
    public function deleted(Branch $branch): void
    {
        $this->flushRegistrant();
    }
}

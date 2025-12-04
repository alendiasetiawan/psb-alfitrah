<?php

namespace App\Observers\Core;

use App\Models\User;
use App\Traits\FlushAdminMasterDataTrait;

class UserObserver
{
    use FlushAdminMasterDataTrait;
    public $afterCommit = true;
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->flushRegistrant();
        $this->flushTotalRegistrant();
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $this->flushRegistrant();
        $this->flushTotalRegistrant();
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->flushRegistrant();
        $this->flushTotalRegistrant();
    }
}

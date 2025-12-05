<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use App\Helpers\CacheKeys\Admin\AdminMasterDataKey;

trait FlushAdminMasterDataTrait
{
    public function flushRegistrant()
    {
        $key = AdminMasterDataKey::adminMasterStudentRegistrant();
        Cache::forget($key);
    }

    public function flushTotalRegistrant()
    {
        $key = AdminMasterDataKey::adminMasterTotalRegistrant();
        Cache::forget($key);
    }

    public function flushStudentOfficial()
    {
        $key = AdminMasterDataKey::adminMasterStudentOfficial();
        Cache::forget($key);
    }

    public function flushTotalStudentOfficialBranch()
    {
        $key = AdminMasterDataKey::adminTotalStudentOfficialBranch();
        Cache::forget($key);
    }
}

<?php

namespace App\Queries\Core;

use App\Models\Core\Job;

class JobQuery {

    public static function getJobBySearchName($search) {
        return Job::where('name', 'like', '%' . $search . '%')->get()->toArray();
    }
}
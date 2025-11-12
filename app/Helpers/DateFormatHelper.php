<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateFormatHelper {

    public static function shortIndoDate($date) {
        return Carbon::parse($date)->locale('id')->isoFormat('D MMM YYYY');
    }
}
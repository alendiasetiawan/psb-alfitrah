<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateFormatHelper
{
    public static function shortIndoDate($date)
    {
        return Carbon::parse($date)->locale('id')->isoFormat('D MMM YYYY');
    }

    public static function shortTime($date)
    {
        return Carbon::parse($date)->locale('id')->isoFormat('HH:mm');
    }

    public static function indoDateTime($date)
    {
        return Carbon::parse($date)->locale('id')->isoFormat('D MMM Y HH:mm');
    }

    public static function longIndoDate($date)
    {
        return Carbon::parse($date)->locale('id')->isoFormat('D MMMM YYYY');
    }
}

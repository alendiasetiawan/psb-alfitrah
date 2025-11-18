<?php

namespace App\Helpers\CacheKeys;

class CoreCacheKey {

    public static function province(): string {
        return "province";
    }

    public static function lastEducation(): string {
        return "last_education";
    }

    public static function sallary(): string {
        return "sallary";
    }

    public static function job(): string {
        return "job";
    }
}
<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class FormatStringHelper {

    public static function initials($name){
        return Str::of($name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public static function limitText($text, $limit = 15, $encoding = 'UTF-8') {
        if (mb_strlen($text, $encoding) > $limit) {
            return mb_substr($text, 0, $limit, $encoding) . '...';
        }
        return $text;
    }

}
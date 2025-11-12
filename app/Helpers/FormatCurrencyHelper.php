<?php

namespace App\Helpers;

class FormatCurrencyHelper
{
    public static function convertToRupiah($value)
    {
        return "Rp ". number_format($value, 0, ',', '.');
    }

    public static function convertCurrency($value)
    {
        return number_format($value, 0, ',', '.');
    }
}
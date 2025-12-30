<?php

namespace App\Queries\PlacementTest;

use App\Models\PlacementTest\TestQrCode;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class TestQrCodeQuery
{
    public static function validQrQuery(string $qrCode): bool
    {
        return DB::table('test_qr_codes')->where('qr', $qrCode)
        ->where('expired_at', '>=', now())
        ->exists();
    }
}
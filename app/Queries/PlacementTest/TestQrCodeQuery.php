<?php

namespace App\Queries\PlacementTest;

use App\Models\PlacementTest\TestQrCode;
use Illuminate\Database\Eloquent\Collection;

class TestQrCodeQuery
{
    public static function validQrQuery(string $qrCode): bool
    {
        return TestQrCode::where('qr', $qrCode)
        ->where('expired_at', '>=', now())
        ->exists();
    }
}
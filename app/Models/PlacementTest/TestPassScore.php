<?php

namespace App\Models\PlacementTest;

use Illuminate\Database\Eloquent\Model;

class TestPassScore extends Model
{
    protected $fillable = [
        'min_final_score',
        'psikotest_weight',
        'read_qruan_weight'
    ];
}

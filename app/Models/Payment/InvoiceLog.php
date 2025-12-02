<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;

class InvoiceLog extends Model
{
    protected $fillable = [
        'event',
        'student_id',
        'external_id',
        'payload'
    ];
}

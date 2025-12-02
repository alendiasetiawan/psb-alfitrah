<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentGateway\XenditWebhookController;


Route::middleware('api-xendit')->group(function () {
    Route::post('/webhook/xendit/confirm-invoice', [XenditWebhookController::class, 'confirmInvoice']);
});


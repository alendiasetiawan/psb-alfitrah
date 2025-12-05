<?php

namespace App\Observers\Payments;

use App\Models\AdmissionData\RegistrationPayment;
use App\Traits\FlushAdminMasterDataTrait;

class RegistrationPaymentObserver
{
    use FlushAdminMasterDataTrait;

    public $afterCommit = true;

    /**
     * Handle the RegistrationPayment "created" event.
     */
    public function created(RegistrationPayment $registrationPayment): void {}

    /**
     * Handle the RegistrationPayment "updated" event.
     */
    public function updated(RegistrationPayment $registrationPayment): void {}

    /**
     * Handle the RegistrationPayment "deleted" event.
     */
    public function deleted(RegistrationPayment $registrationPayment): void {}
}

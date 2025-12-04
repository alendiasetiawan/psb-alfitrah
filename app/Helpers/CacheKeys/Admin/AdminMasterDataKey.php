<?php

namespace App\Helpers\CacheKeys\Admin;

class AdminMasterDataKey
{
    //Related table: registration_payments, users, students, branches
    public static function adminMasterStudentRegistrant(): string
    {
        return "admin_master_student_registrant";
    }

    //Related table: students, users
    public static function adminMasterTotalRegistrant(): string
    {
        return "admin_master_total_registrant";
    }
}

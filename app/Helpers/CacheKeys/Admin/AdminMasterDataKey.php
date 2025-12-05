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

    //Related table: placement_test_results, students
    public static function adminMasterStudentOfficial(): string
    {
        return "admin_master_student_official";
    }

    //Related table: placement_test_results, students, branches
    public static function adminTotalStudentOfficialBranch(): string
    {
        return "admin_total_student_official_branch";
    }
}

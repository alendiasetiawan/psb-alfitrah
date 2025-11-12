<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $thisYear = now()->year;
        $nextYear = $thisYear + 1;
        $academicYear = $thisYear . '-' . $nextYear;

        DB::table('admissions')->insert([
            'name' => $academicYear
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EducationProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('education_programs')->insert([
            [
                'branch_id' => 1,
                'name' => 'SMP Tahfidz',
                'description' => 'Program SMP Tahfidz',
            ], 
            [
                'branch_id' => 1,
                'name' => 'SMA Tahfidz',
                'description' => 'Program SMA Tahfidz',
            ]
        ]);
    }
}

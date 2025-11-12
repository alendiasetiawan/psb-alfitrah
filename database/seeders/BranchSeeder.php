<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('branches')->insert([
            'name' => 'Al Fitrah 1 Jonggol',
            'address' => 'Kp. Rawa Makmur No.64, RT.02/RW.02, Singajaya, Kec. Jonggol, Kabupaten Bogor, Jawa Barat, 16830.',
            'mobile_phone' => '0812-3456-7890',
            'map_link' => 'https://maps.app.goo.gl/PWxwhJAUNkWJ3P1k6',
            'photo' => null,
        ]);
    }
}

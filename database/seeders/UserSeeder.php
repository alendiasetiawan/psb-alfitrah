<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'role_id' =>  1,
                'username' => 'adminpsb',
                'password' => Hash::make('password'),
                'fullname' => 'Admin PSB',
                'gender' => 'Laki-laki',
                'is_verified'  => 1,
                'verified_at' => now()
            ],
            [
                'role_id' =>  2,
                'username' => 'yayasan',
                'password' => Hash::make('password'),
                'fullname' => 'Pengurus Yayasan',
                'gender' => 'Laki-laki',
                'is_verified'  => 1,
                'verified_at' => now()
            ],
        ]);
    }
}

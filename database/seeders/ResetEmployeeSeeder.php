<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userPn = '403';
        $newPassword = '403'; // atau password baru sesuai kebutuhan

        DB::table('tbl_user_hris_absen')->where('PersonnelNo', $userPn)->update([
            'password' => Hash::make($newPassword),
            'updated_at' => now(),
        ]);
    }
}

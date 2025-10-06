<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('program_studi')->insert([
            ['nama' => 'Akuntansi', 'tingkat' => 'D3', 'fakultas_id' => 1],
            ['nama' => 'Administrasi Bisnis', 'tingkat' => 'S1', 'fakultas_id' => 1],
            ['nama' => 'Manajemen', 'tingkat' => 'S1', 'fakultas_id' => 1],
            ['nama' => 'Akuntansi', 'tingkat' => 'S1', 'fakultas_id' => 1],
            ['nama' => 'Magister Akuntansi', 'tingkat' => 'S2', 'fakultas_id' => 1],
            ['nama' => 'Magister Bisnis', 'tingkat' => 'S2', 'fakultas_id' => 1],
            ['nama' => 'Teknik Informatika', 'tingkat' => 'S1', 'fakultas_id' => 2],
            ['nama' => 'Sistem Informasi', 'tingkat' => 'S1', 'fakultas_id' => 2],
            ['nama' => 'Teknik Perangkat Lunak', 'tingkat' => 'S1', 'fakultas_id' => 2],
            ['nama' => 'Teknik Elektro', 'tingkat' => 'S1', 'fakultas_id' => 2],
            ['nama' => 'Teknik Industri', 'tingkat' => 'S1', 'fakultas_id' => 2],
            ['nama' => 'Ilmu Komunikasi', 'tingkat' => 'S1', 'fakultas_id' => 3],
            ['nama' => 'Sastra Inggris', 'tingkat' => 'S1', 'fakultas_id' => 3],
            ['nama' => 'Bahasa Inggris', 'tingkat' => 'D3', 'fakultas_id' => 3],
        ]);
    }
}

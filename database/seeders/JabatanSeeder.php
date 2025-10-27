<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB; 

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    public function run()
{
    DB::table('jabatan')->insert([
        ['nama' => 'Asisten Ahli'],
        ['nama' => 'Lektor'],
        ['nama' => 'Lektor Kepala'],
        ['nama' => 'Guru Besar'],
    ]);
}
}

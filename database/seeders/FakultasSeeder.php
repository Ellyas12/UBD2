<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB; 

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FakultasSeeder extends Seeder
{

   public function run()
{
    DB::table('fakultas')->insert([
        ['nama' => 'Fakultas Teknik'],
        ['nama' => 'Fakultas Ekonomi'],
        ['nama' => 'Fakultas Hukum'],
    ]);
}
}

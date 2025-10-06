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
        ['nama' => 'Fakultas Bisnis'],
        ['nama' => 'Fakultas Sains dan Teknologi'],
        ['nama' => 'Fakultas Sosial dan Humaniora'],
    ]);
}
}

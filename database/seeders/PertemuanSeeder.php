<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB; 

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PertemuanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pertemuan')->insert([
            ['nama' => 'Pertemuan 1'],
            ['nama' => 'Pertemuan 2'],
            ['nama' => 'Pertemuan 3'],
            ['nama' => 'Pertemuan 4'],
            ['nama' => 'Pertemuan 5'],
            ['nama' => 'Pertemuan 6'],
            ['nama' => 'Pertemuan 7'],
            ['nama' => 'Pertemuan 8'],
            ['nama' => 'Pertemuan 9'],
            ['nama' => 'Pertemuan 10'],
            ['nama' => 'Pertemuan 11'],
            ['nama' => 'Pertemuan 12'],
            ['nama' => 'Pertemuan 13'],
            ['nama' => 'Pertemuan 14'],
            ['nama' => 'Pertemuan 15'],
            ['nama' => 'Pertemuan 16'],
            ['nama' => 'Pertemuan 17'],
            ['nama' => 'Pertemuan 18'],
            ['nama' => 'Pertemuan 19'],
            ['nama' => 'Pertemuan 20'],
        ]);
    }
}

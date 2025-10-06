<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
                    FakultasSeeder::class,
                    ProdiSeeder::class,
                    JabatanSeeder::class,
                    PertemuanSeeder::class,
                    UserSeeder::class
    ]);
    }
}

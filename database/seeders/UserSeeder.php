<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
DB::table('users')->updateOrInsert(
    ['email' => 'estzwei12@gmail.com'],
    [
        'username' => 'admin',
        'password' => bcrypt(Str::random(40)),
        'nidn' => '000000',
        'role' => 'Admin',
    ]
);
    }
}
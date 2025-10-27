<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
DB::table('users')->insert([
    [
        'email' => 'william2@gmail.com',
        'username' => 'admin',
        'password' => bcrypt(Str::random(40)),
        'nidn' => '000000',
        'role' => 'Admin',
    ],
    [
        'email' => 'estzwei12@gmail.com',
        'username' => 'William12',
        'password' => bcrypt('Blaem123'),
        'nidn' => '123123',
        'role' => 'Lecturer',
    ],
]);
    }
}
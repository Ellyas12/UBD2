<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah'; // Table name
    protected $primaryKey = 'matkul_id'; // Primary key

    // If your PK is auto-increment and integer, no need to change these:
    public $incrementing = true;
    protected $keyType = 'int';

    // Mass assignable columns
    protected $fillable = [
        'kode_matkul',
        'nama',
        'SKS',
    ];
}
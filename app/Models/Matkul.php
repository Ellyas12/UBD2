<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matkul extends Model
{
    use HasFactory;

    protected $table = 'matkul';
    protected $primaryKey = 'matkul_id';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'kode_matkul',
        'nama',
        'SKS',
    ];

    public function matdos()
    {
        return $this->hasMany(Matdos::class, 'dosen_id', 'dosen_id');
    }
}
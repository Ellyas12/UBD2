<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matdos extends Model
{
    use HasFactory;

    protected $table = 'matdos';
    protected $primaryKey = 'matdos_id';
    public $timestamps = false;

    protected $fillable = [
        'dosen_id',
        'matkul_id',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id', 'dosen_id');
    }

    public function matkul()
    {
        return $this->belongsTo(Matkul::class, 'matkul_id', 'matkul_id');
    }
}

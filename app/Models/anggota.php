<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;
    
    protected $table = 'anggota';
    protected $primaryKey = 'anggota_id';
    public $timestamps = false;

    protected $fillable = [
        'program_id',
        'dosen_id',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'program_id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id', 'dosen_id');
    }
}

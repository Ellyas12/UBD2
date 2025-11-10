<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';
    protected $primaryKey = 'dosen_id';

    protected $fillable = [
        'user_id',
        'nama',
        'telp',
        'pendidikan',
        'bidang',
        'profile_picture',
        'jabatan_id',
        'fakultas_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id', 'fakultas_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id', 'jabatan_id');
    }

    public function program()
    {
        return $this->hasMany(Program::class, 'dosen_id', 'dosen_id');
    }

    public function matdos()
    {
        return $this->hasMany(Matdos::class, 'dosen_id', 'dosen_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $table = 'program';
    protected $primaryKey = 'program_id';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'jenis',
        'bidang',
        'topik',
        'judul',
        'ketua',
        'anggota',
        'tanggal',
        'biaya',
        'sumber_biaya',
        'dosen_id',
        'pertemuan_id',
        'deskripsi',
        'linkpdf',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id', 'dosen_id');
    }

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class, 'pertemuan_id', 'pertemuan_id');
    }
}
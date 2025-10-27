<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramBackup extends Model
{
    protected $table = 'program_backup';
    protected $primaryKey = 'backup_id';
    protected $fillable = [
        'program_id', 
        'jenis', 
        'bidang', 
        'topik', 
        'judul', 
        'ketua',
        'anggota', 
        'tanggal', 
        'biaya', 
        'sumber_biaya',
        'deskripsi',
        'status',
        'stamp',
        'comment',
        'dosen_id', 
        'pertemuan_id',
        'deleted_at', 
        'deleted_by'
    ];

    public function filesBackup()
    {
        return $this->hasMany(FileBackup::class, 'program_id', 'program_id');
    }
}

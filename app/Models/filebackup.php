<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileBackup extends Model
{
    use HasFactory;

    protected $table = 'file_backup';
    protected $primaryKey = 'file_backup_id';

    protected $fillable = [
        'program_id',
        'nama',
        'file',
        'folder',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    public function programBackup()
    {
        return $this->belongsTo(ProgramBackup::class, 'program_id', 'program_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramBackup extends Model
{
    protected $table = 'programbackup';
    protected $primaryKey = 'programbackup_id';
    public $timestamps = true;
    protected $fillable = [
        'program_id', 
        'backup_code',
        'created_at',
        'updated_at',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'program_id')
                    ->withTrashed(); // 👈 include soft-deleted rows
    }
}

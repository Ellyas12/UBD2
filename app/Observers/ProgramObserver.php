<?php
namespace App\Observers;

use App\Models\Program;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class ProgramObserver
{
    public function created(Program $program)
    {
        $this->logAction('created', $program);
    }

    public function updated(Program $program)
    {
        $this->logAction('updated', $program);
    }

    public function deleted(Program $program)
    {
        $this->logAction('deleted', $program);
    }

    public function restored(Program $program)
    {
        $this->logAction('restored', $program);
    }

    private function logAction(string $action, Program $program)
    {
        $user = Auth::user();

        // ✅ Do not log if no user OR if the user is an admin
        if (!$user || $user->role === 'Admin') {
            return;
        }

        // ✅ Get the correct display name (dosen.nama)
        $nama = $user->dosen->nama ?? $user->username ?? 'Unknown User';

        Log::create([
            'user_id' => $user->user_id, // ✅ correct primary key
            'action'  => $action,
            'model'   => 'Program',
            'description' => sprintf(
                '"%s" %s program "%s" at %s',
                $nama,
                $action,
                $program->judul ?? '(no title)',
                now()->format('F d, Y h:i A') // ✅ full month + day + year
            ),
        ]);
    }
}


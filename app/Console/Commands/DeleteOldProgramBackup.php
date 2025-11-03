<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File as FileFacade;
use App\Models\ProgramBackup;
use Carbon\Carbon;

class DeleteOldProgramBackups extends Command
{
    protected $signature = 'backups:clean';
    protected $description = 'Delete program backups older than 7 days that were not restored.';

    public function handle()
    {
        $threshold = Carbon::now()->subDays(1); // change to 7 days if you want
        $oldBackups = ProgramBackup::where('created_at', '<', $threshold)->get();

        if ($oldBackups->isEmpty()) {
            $this->info('No old backups found.');
            return Command::SUCCESS;
        }

        foreach ($oldBackups as $backup) {
            $program = $backup->program;

            if ($program) {
                $programId = $program->program_id;

                // ✅ 1. Delete backup folder
                $backupPattern = public_path("storage/backups/program_{$programId}_*");
                $backupFolders = glob($backupPattern);

                foreach ($backupFolders as $folder) {
                    if (FileFacade::exists($folder)) {
                        FileFacade::deleteDirectory($folder);
                        $this->info("🗑️ Deleted backup folder: {$folder}");
                    }
                }

                // ✅ 2. Delete the Program record
                $program->forceDelete();
                $this->info("🧾 Deleted program record (ID: {$programId}).");
            } else {
                $this->warn("⚠️ Program with ID {$backup->program_id} not found.");
            }

            // ✅ 3. Delete the backup record itself
            $backup->delete();
            $this->info("🗂️ Deleted backup record for Program ID: {$backup->program_id}.");
        }

        $this->info('✅ Old program backups successfully cleaned up.');
        return Command::SUCCESS;
    }
}

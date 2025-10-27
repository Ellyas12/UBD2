<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File as FileFacade;
use App\Models\ProgramBackup;
use App\Models\FileBackup;
use Carbon\Carbon;

class CleanOldBackups extends Command
{
    protected $signature = 'clean:backups {--days=30 : Delete backups older than this number of days}';
    protected $description = 'Delete old backups (DB + physical files) older than N days. Default: 30 days.';

    public function handle()
    {
        $days = (int) $this->option('days');
        $cutoff = Carbon::now()->subDays($days);

        $this->info("🧹 Cleaning backups older than {$days} days...");

        // 1️⃣ Delete old backup folders
        $backupDir = public_path('storage/backups');
        if (FileFacade::exists($backupDir)) {
            $folders = FileFacade::directories($backupDir);

            foreach ($folders as $folder) {
                $lastModified = Carbon::createFromTimestamp(FileFacade::lastModified($folder));
                if ($lastModified->lt($cutoff)) {
                    FileFacade::deleteDirectory($folder);
                    $this->line("🗑️ Deleted old folder: " . basename($folder));
                }
            }
        }

        // 2️⃣ Delete old database records
        $deletedPrograms = ProgramBackup::where('deleted_at', '<', $cutoff)->delete();
        $deletedFiles = FileBackup::where('created_at', '<', $cutoff)->delete();

        $this->info("✅ Deleted {$deletedPrograms} program backups and {$deletedFiles} file backups older than {$days} days.");

        return Command::SUCCESS;
    }
}
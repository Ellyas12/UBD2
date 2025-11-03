<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\DeleteOldProgramBackups;

Artisan::command('backups:clean', function () {
    $command = new DeleteOldProgramBackups();
    $command->setLaravel(app());     // inject app
    $command->setOutput($this->output); // inject output handler
    $command->handle();
})->describe('Delete program backups older than 7 days that were not restored.');

Schedule::command('backups:clean')->weekly(1, '02:00');


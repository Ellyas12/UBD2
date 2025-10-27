<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_backup', function (Blueprint $table) {
            $table->id('file_backup_id');
            $table->unsignedBigInteger('program_id')->nullable();
            $table->string('nama');
            $table->string('file');
            $table->string('folder');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_backup');
    }
};
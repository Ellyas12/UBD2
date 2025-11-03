<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programbackup', function (Blueprint $table) {
        $table->id('programbackup_id');
        $table->unsignedBigInteger('program_id');
        $table->string('backup_code')->unique();
        $table->timestamps();

        $table->foreign('program_id')->references('program_id')->on('program')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programbackup');
    }
};

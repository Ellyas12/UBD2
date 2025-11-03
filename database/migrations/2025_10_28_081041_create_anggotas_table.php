<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->id('anggota_id');

            $table->unsignedBigInteger('program_id');
            $table->foreign('program_id')
                  ->references('program_id')
                  ->on('program')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('dosen_id');
            $table->foreign('dosen_id')
                  ->references('dosen_id')
                  ->on('dosen')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anggota');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestasi', function (Blueprint $table) {
            $table->id('prestasi_id');
            $table->unsignedBigInteger('dosen_id')->nullable();
            $table->foreign('dosen_id')
                  ->references('dosen_id')
                  ->on('dosen')
                  ->onDelete('set null');
            $table->string('nama', 100);
            $table->string('Link', 400);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('prestasi');
    }
};

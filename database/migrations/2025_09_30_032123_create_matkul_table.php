<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matkul', function (Blueprint $table) {
            $table->id('matkul_id');
            $table->string('kode_matkul', 100);
            $table->string('nama', 100);
            $table->string('SKS', 100);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matkul');
    }
};

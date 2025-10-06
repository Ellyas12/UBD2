<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matkuls', function (Blueprint $table) {
            $table->id('matkul_id'); // INT AUTO_INCREMENT PRIMARY KEY
            $table->string('kode_matkul', 100); // VARCHAR(100) NOT NULL
            $table->string('nama', 100);        // VARCHAR(100) NOT NULL
            $table->string('SKS', 100);         // VARCHAR(100) NOT NULL
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matkuls');
    }
};

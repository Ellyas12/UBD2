<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pertemuan', function (Blueprint $table) {
            $table->id('pertemuan_id');
            $table->string('nama');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertemuan');
    }
};

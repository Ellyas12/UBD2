<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('program_id');
            $table->enum('status', ['Create', 'Update', 'Delete', 'Restored']);
            $table->string('Judul')->nullable();
            $table->string('Deletor')->nullable();
            $table->string('Author')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log');
    }
};

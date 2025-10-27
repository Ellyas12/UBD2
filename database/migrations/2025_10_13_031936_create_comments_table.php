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
        Schema::create('comment', function (Blueprint $table) {
            $table->id('comment_id');

            $table->unsignedBigInteger('program_id')->unique();
            $table->foreign('program_id')
                  ->references('program_id')
                  ->on('program')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('dosen_id');
            $table->foreign('dosen_id')
                  ->references('dosen_id')
                  ->on('dosen')
                  ->onDelete('cascade');

            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comment');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program', function (Blueprint $table) {
            $table->id('program_id');
            $table->string('jenis', 100);
            $table->string('bidang', 100);
            $table->string('topik', 100);
            $table->string('judul', 100);
            $table->date('tanggal');
            $table->string('biaya', 100)->nullable();
            $table->string('sumber_biaya', 100)->nullable();
            $table->string('linkweb', 100)->nullable();
            $table->enum('status', ['Pending', 'Accepted', 'Denied', 'Revisi'])->default('Pending');
            $table->enum('stamp', ['Done', 'Not yet'])->default('Not yet');

            $table->text('deskripsi')->nullable();

            $table->unsignedBigInteger('dosen_id')->nullable();
            $table->unsignedBigInteger('pertemuan_id')->nullable();

            $table->foreign('dosen_id')
                  ->references('dosen_id')
                  ->on('dosen')
                  ->onDelete('set null');

            $table->foreign('pertemuan_id')
                  ->references('pertemuan_id')
                  ->on('pertemuan')
                  ->onDelete('set null');

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program');
    }
};
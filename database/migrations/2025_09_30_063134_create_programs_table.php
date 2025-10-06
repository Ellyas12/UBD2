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
        Schema::create('program', function (Blueprint $table) {
            $table->id('program_id');
            $table->string('jenis', 100);
            $table->string('bidang', 100);
            $table->string('topik', 100);
            $table->string('judul', 100);
            $table->string('ketua', 100);
            $table->string('anggota', 100)->nullable();
            $table->date('tanggal');
            $table->string('biaya', 100);
            $table->string('sumber_biaya', 100);

            // Updated fields
            $table->text('deskripsi')->nullable();   // long text
            $table->string('linkpdf', 100)->nullable();

            // Foreign keys
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
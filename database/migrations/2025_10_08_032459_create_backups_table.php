<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_backup', function (Blueprint $table) {
            $table->id('backup_id');

            $table->unsignedBigInteger('program_id')->nullable();
            $table->string('jenis', 100);
            $table->string('bidang', 100);
            $table->string('topik', 100);
            $table->string('judul', 100);
            $table->string('ketua', 100);
            $table->string('anggota', 100)->nullable();
            $table->date('tanggal');
            $table->string('biaya', 100)->nullable();
            $table->string('sumber_biaya', 100)->nullable();
            $table->string('linkweb', 100)->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['Pending', 'Accepted', 'Denied', 'Revisi'])->default('Pending');
            $table->enum('stamp', ['Done', 'Not yet'])->default('Not yet');
            $table->text('comment',100)->nullable(); 

            $table->unsignedBigInteger('dosen_id')->nullable();
            $table->unsignedBigInteger('pertemuan_id')->nullable();

            // Track backup info
            $table->timestamp('deleted_at')->useCurrent();
            $table->string('deleted_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_backup');
    }
};

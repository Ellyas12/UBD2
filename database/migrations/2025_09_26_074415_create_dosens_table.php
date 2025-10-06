<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosen', function (Blueprint $table) {
            $table->id('dosen_id');
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('nama', 100);
            $table->string('telp', 20)->nullable();
            $table->string('pendidikan', 40)->nullable();            
            $table->string('bidang', 40)->nullable();            
            $table->string('profile_picture', 100)->nullable();
            $table->unsignedBigInteger('jabatan_id')->nullable();
            $table->unsignedBigInteger('fakultas_id')->nullable();
            
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('jabatan_id')->references('jabatan_id')->on('jabatan')->onDelete('set null');
            $table->foreign('fakultas_id')->references('fakultas_id')->on('fakultas')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('jabatan', function (Blueprint $table) {
        $table->id('jabatan_id');
        $table->string('nama');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('jabatans');
    }
};

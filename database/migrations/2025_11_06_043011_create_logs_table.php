<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log', function (Blueprint $table) {
            $table->id();

            // ✅ Must match users.user_id EXACTLY
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('action');
            $table->string('model');
            $table->text('description');
            $table->timestamps();

            // ✅ Correct foreign key reference
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log');
    }
};

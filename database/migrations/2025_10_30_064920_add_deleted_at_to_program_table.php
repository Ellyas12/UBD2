<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program', function (Blueprint $table) {
            // ✅ Add deleted_at column if not exists
            if (!Schema::hasColumn('program', 'deleted_at')) {
                $table->softDeletes(); // adds a nullable deleted_at TIMESTAMP column
            }
        });
    }

    public function down(): void
    {
        Schema::table('program', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};

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
        // Add deleted_at column to suppliers table for soft deletes support
        if (! Schema::hasColumn('suppliers', 'deleted_at')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('suppliers', 'deleted_at')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};

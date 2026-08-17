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
        // These columns were created with inconsistent casing (Expirydate, Unitprice,
        // Reorderlevel) that never matched the Item model's fillable/casts
        // (expiry_date, unit_price, reorder_level) — inserts/updates touching these
        // fields were silently failing with "column not found". Renaming to match.
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'Expirydate') && ! Schema::hasColumn('items', 'expiry_date')) {
                $table->renameColumn('Expirydate', 'expiry_date');
            }
            if (Schema::hasColumn('items', 'Unitprice') && ! Schema::hasColumn('items', 'unit_price')) {
                $table->renameColumn('Unitprice', 'unit_price');
            }
            if (Schema::hasColumn('items', 'Reorderlevel') && ! Schema::hasColumn('items', 'reorder_level')) {
                $table->renameColumn('Reorderlevel', 'reorder_level');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            if (Schema::hasColumn('items', 'expiry_date')) {
                $table->renameColumn('expiry_date', 'Expirydate');
            }
            if (Schema::hasColumn('items', 'unit_price')) {
                $table->renameColumn('unit_price', 'Unitprice');
            }
            if (Schema::hasColumn('items', 'reorder_level')) {
                $table->renameColumn('reorder_level', 'Reorderlevel');
            }
        });
    }
};

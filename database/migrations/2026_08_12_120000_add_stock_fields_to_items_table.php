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
        Schema::table('items', function (Blueprint $table) {
            if (! Schema::hasColumn('items', 'quantity')) {
                $table->float('quantity')->default(0);
            }
            if (! Schema::hasColumn('items', 'Expirydate')) {
                $table->date('Expirydate')->nullable();
            }
            if (! Schema::hasColumn('items', 'Unitprice')) {
                $table->float('Unitprice')->nullable();
            }
            if (! Schema::hasColumn('items', 'Reorderlevel')) {
                $table->float('Reorderlevel')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'Expirydate', 'Unitprice', 'Reorderlevel']);
        });
    }
};

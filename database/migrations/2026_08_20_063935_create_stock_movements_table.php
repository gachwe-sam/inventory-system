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
        schema::create('stock_movements', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->float('quantity_change');
            $table->string('type'); //purchases,sales,adjustments,transferin etc
            $table->string('reference_id')->nullable(); // links the two legs of a transfer, not a foreign key
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('notes')->nullable();
            $table->timestamp('created_at')->usecurrent(); // a ledger table should never be editable

            $table->index(['branch_id','item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::dropIfExists('stock_movements');
    }
};

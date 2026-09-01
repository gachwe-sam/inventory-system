<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::table('suppliers', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->nullOnDelete();
        });

        
        Schema::table('purchases', function (Blueprint $table) {
            $table->foreign('item_id')->references('id')->on('items')->nullOnDelete();

            
            $table->softDeletes();
        });
    }

    
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropSoftDeletes();
        });
    }
};
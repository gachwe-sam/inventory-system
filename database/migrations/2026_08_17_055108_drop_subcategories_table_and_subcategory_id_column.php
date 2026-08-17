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
        if (Schema::hasColumn('items', 'subcategory_id')) {
            Schema::table('items', function (Blueprint $table) {
                $table->dropConstrainedForeignId('subcategory_id');
            });
        }

        Schema::dropIfExists('subcategories');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('subcategories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        if (! Schema::hasColumn('items', 'subcategory_id')) {
            Schema::table('items', function (Blueprint $table) {
                $table->foreignId('subcategory_id')->nullable()->after('category_id')
                    ->constrained('subcategories')->nullOnDelete();
            });
        }
    }
};

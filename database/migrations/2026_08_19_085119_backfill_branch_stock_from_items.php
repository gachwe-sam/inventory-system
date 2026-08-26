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
    $defaultBranchId = DB::table('branches')->whereNull('parent_id')->value('id');

    if (! $defaultBranchId) {
        $defaultBranchId = DB::table('branches')->insertGetId([
            'name' => 'Head Office',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    DB::table('items')->select('id', 'quantity', 'reorder_level')->orderBy('id')
        ->chunk(200, function ($items) use ($defaultBranchId) {
            foreach ($items as $item) {
                DB::table('branch_stock')->insert([
                    'branch_id' => $defaultBranchId,
                    'item_id' => $item->id,
                    'quantity' => $item->quantity ?? 0,
                    'reorder_level' => $item->reorder_level ?? 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            //
        });
    }
};

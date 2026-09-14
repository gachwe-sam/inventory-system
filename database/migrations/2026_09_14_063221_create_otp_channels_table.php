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
        Schema::create('otp_channels', function (Blueprint $table) {
            $table->id();
            $table->string('channel')->unique();
            $table->boolean('enabled')->default(false);
            $table->timestamps();
        });

        DB::table('otp_channels')->insert([
            ['channel' => 'email', 'enabled' => true,'created_at' => now(), 'updated_at' => now()],
            ['channel' => 'sms', 'enabled' => false,'created_at' => now(), 'updated_at' => now()],
            ['channel' => 'whatsapp', 'enabled' => false,'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_channels');
    }
};

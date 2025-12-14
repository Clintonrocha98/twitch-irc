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
        Schema::create('channel_user_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_user_id')->constrained('provider_users')->cascadeOnDelete();
            $table->string('channel');
            $table->unsignedInteger('total_messages')->default(0);
            $table->unsignedInteger('xp')->default(0);
            $table->unsignedBigInteger('total_watch_time')->default(0);
            $table->timestamps();

            $table->unique(['provider_user_id', 'channel']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channel_user_metrics');
    }
};

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
        Schema::table('ai_logs', function (Blueprint $table) {
            $table->foreign(['outfit_id'], 'ai_logs_outfit_id_fkey')->references(['id'])->on('outfits')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['user_id'], 'ai_logs_user_id_fkey')->references(['id'])->on('users')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_logs', function (Blueprint $table) {
            $table->dropForeign('ai_logs_outfit_id_fkey');
            $table->dropForeign('ai_logs_user_id_fkey');
        });
    }
};

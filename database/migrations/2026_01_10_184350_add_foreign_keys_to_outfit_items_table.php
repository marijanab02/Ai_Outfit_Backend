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
        Schema::table('outfit_items', function (Blueprint $table) {
            $table->foreign(['clothing_item_id'], 'outfit_items_clothing_item_id_fkey')->references(['id'])->on('clothing_items')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['outfit_id'], 'outfit_items_outfit_id_fkey')->references(['id'])->on('outfits')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outfit_items', function (Blueprint $table) {
            $table->dropForeign('outfit_items_clothing_item_id_fkey');
            $table->dropForeign('outfit_items_outfit_id_fkey');
        });
    }
};

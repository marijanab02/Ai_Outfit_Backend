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
        Schema::create('outfit_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('outfit_id')->index('idx_outfit_items_outfit_id');
            $table->bigInteger('clothing_item_id')->index('idx_outfit_items_clothing_item_id');

            $table->unique(['outfit_id', 'clothing_item_id'], 'outfit_items_outfit_id_clothing_item_id_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outfit_items');
    }
};

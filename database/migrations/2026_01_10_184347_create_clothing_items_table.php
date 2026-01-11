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
        Schema::create('clothing_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->index('idx_clothing_items_user_id');
            $table->string('name')->nullable();
            $table->string('category', 50)->nullable();
            $table->string('color', 50)->nullable();
            $table->text('image_url')->nullable();
            $table->string('season', 50)->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clothing_items');
    }
};

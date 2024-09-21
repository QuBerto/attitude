<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('loot_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loot_id')->constrained('loots')->onDelete('cascade');
            $table->integer('item_id');
            $table->integer('quantity');
            $table->integer('price_each');
            $table->string('name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loot_items');
    }
};

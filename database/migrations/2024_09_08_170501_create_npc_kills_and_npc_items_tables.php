<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNpcKillsAndNpcItemsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create the npc_kills table
        Schema::create('npc_kills', function (Blueprint $table) {
            $table->id();
            $table->integer('npc_id'); // NPC ID
            $table->integer('ge_price'); // Grand Exchange Price
            $table->bigInteger('timestamp'); // Unix timestamp
            $table->timestamps(); // Laravel's created_at and updated_at fields
        });

        // Create the npc_items table
        Schema::create('npc_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('npc_kill_id')->constrained('npc_kills')->onDelete('cascade'); // Foreign key to npc_kills
            $table->integer('item_id'); // Item ID
            $table->integer('quantity'); // Quantity of the item
            $table->timestamps(); // Laravel's created_at and updated_at fields
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('npc_items');
        Schema::dropIfExists('npc_kills');
    }
}

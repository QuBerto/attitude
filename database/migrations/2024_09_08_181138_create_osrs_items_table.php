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
        Schema::create('osrs_items', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->integer('item_id')->unique(); // Unique OSRS item ID
            $table->string('name'); // Name of the item
            $table->integer('value')->nullable(); // Optional value of the item (e.g., GE price)
            $table->string('description')->nullable(); // Optional item description
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
        Schema::dropIfExists('osrs_items');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_status', function (Blueprint $table) {
            $table->id();
            $table->string('user_name'); // Player's userName
            $table->string('account_type'); // Account type (NORMAL, etc.)
            $table->integer('combat_level'); // Combat level
            $table->integer('world'); // World number
            $table->integer('world_x'); // X coordinate in worldPoint
            $table->integer('world_y'); // Y coordinate in worldPoint
            $table->integer('world_plane'); // Plane in worldPoint
            $table->integer('max_health'); // Maximum health
            $table->integer('current_health'); // Current health
            $table->integer('max_prayer'); // Maximum prayer
            $table->integer('current_prayer'); // Current prayer
            $table->integer('current_run'); // Current run energy
            $table->integer('current_weight'); // Current weight
            $table->bigInteger('timestamp'); // Timestamp from the request

            // Foreign key to discord_user
            $table->foreignId('discord_user_id')->constrained('discord_users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_status');
    }
};

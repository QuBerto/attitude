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
        Schema::create('discord_user_role', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discord_user_id')->constrained('discord_users')->onDelete('cascade');
            $table->foreignId('discord_role_id')->constrained('discord_roles')->onDelete('cascade');
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
        Schema::dropIfExists('discord_user_role');
    }
};

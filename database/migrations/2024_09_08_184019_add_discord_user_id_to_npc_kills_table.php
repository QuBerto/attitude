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
        Schema::table('npc_kills', function (Blueprint $table) {
            // Add the discord_user_id foreign key
            $table->foreignId('discord_user_id')->nullable()->constrained('discord_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('npc_kills', function (Blueprint $table) {
            // Drop the foreign key and column if the migration is rolled back
            $table->dropForeign(['discord_user_id']);
            $table->dropColumn('discord_user_id');
        });
    }
};

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
        Schema::table('r_s_accounts', function (Blueprint $table) {
            $table->foreignId('discord_user_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('r_s_accounts', function (Blueprint $table) {
            $table->dropForeign(['discord_user_id']);
            $table->dropColumn('discord_user_id');
        });
    }
};

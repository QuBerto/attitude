<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToRsAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('r_s_accounts', function (Blueprint $table) {
            $table->string('role')->nullable()->after('discord_user_id'); // Add the 'role' column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rs_accounts', function (Blueprint $table) {
            $table->dropColumn('role'); // Remove the 'role' column if rolled back
        });
    }
}

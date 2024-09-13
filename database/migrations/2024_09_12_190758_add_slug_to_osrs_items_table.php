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
        Schema::table('osrs_items', function (Blueprint $table) {
            // Add the 'slug' column after the 'name' column
            $table->string('slug')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('osrs_items', function (Blueprint $table) {
            // Drop the 'slug' column if the migration is rolled back
            $table->dropColumn('slug');
        });
    }
};

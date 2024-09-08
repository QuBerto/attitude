<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('osrs_items', function (Blueprint $table) {
            // Add the 'type' column to hold 'manual', 'connected', or 'api'
            $table->enum('type', ['manual', 'connected', 'api'])->default('manual')->after('description');
            
            // Add the 'parent_id' column that can be null, referring to another item
            $table->unsignedBigInteger('parent_id')->nullable()->after('type');
            
            // Optionally, create a foreign key constraint if parent_id references the osrs_items table itself
            $table->foreign('parent_id')->references('id')->on('osrs_items')->onDelete('cascade');
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
            // Drop the foreign key constraint and the columns
            $table->dropForeign(['parent_id']);
            $table->dropColumn('type');
            $table->dropColumn('parent_id');
        });
    }
};

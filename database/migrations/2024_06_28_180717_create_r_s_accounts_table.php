<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRSAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('r_s_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('wom_id')->unique();
            $table->string('username');
            $table->string('display_name')->nullable();
            $table->string('type')->nullable();
            $table->string('build')->nullable();
            $table->string('status')->nullable();
            $table->string('country')->nullable();
            $table->boolean('patron')->default(false);
            $table->bigInteger('exp')->nullable();
            $table->float('ehp')->nullable();
            $table->float('ehb')->nullable();
            $table->float('ttm')->nullable();
            $table->float('tt200m')->nullable();
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('wom_updated_at')->nullable();
            $table->timestamp('last_changed_at')->nullable();
            $table->timestamp('last_imported_at')->nullable();
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
        Schema::dropIfExists('r_s_accounts');
    }
}

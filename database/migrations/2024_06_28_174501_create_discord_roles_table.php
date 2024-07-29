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
        Schema::create('discord_roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_id')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->bigInteger('permissions')->default(0);
            $table->string('permissions_new');
            $table->integer('position')->default(0);
            $table->integer('color')->default(0);
            $table->boolean('hoist')->default(false);
            $table->boolean('managed')->default(false);
            $table->boolean('mentionable')->default(false);
            $table->string('icon')->nullable();
            $table->string('unicode_emoji')->nullable();
            $table->bigInteger('flags')->default(0);
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
        Schema::dropIfExists('discord_roles');
    }
};

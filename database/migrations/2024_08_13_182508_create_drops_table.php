<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDropsTable extends Migration
{
    public function up()
    {
        Schema::create('drops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained('r_s_accounts');
            $table->string('eventcode');
            $table->string('itemsource');
            $table->json('items');
            $table->integer('gp')->default(0); // Add gp column with default value 0
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('drops');
    }
}


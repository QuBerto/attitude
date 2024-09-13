<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmojisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emojis', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('emoji_id'); // Emoji ID as a string
            $table->string('name');     // Name of the emoji
            $table->timestamps();       // Timestamps for created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emojis');
    }
}


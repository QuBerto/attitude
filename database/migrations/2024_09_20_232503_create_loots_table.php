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
    Schema::create('loots', function (Blueprint $table) {
        $table->id();
        $table->string('source');
        $table->string('category');
        $table->integer('kill_count')->default(0);
        $table->integer('value')->default(0);
        $table->timestamps();
    });
    
    Schema::table('loots', function (Blueprint $table) {
        $table->foreignId('rs_account_id')->nullable()->constrained('r_s_accounts')->onDelete('cascade')->after('value');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loots');
    }
};

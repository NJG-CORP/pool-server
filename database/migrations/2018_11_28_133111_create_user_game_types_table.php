<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGameTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_game_types', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger("user_id");
            $table->smallInteger('pool')->default(0);
            $table->smallInteger('snooker')->default(0);
            $table->smallInteger('russian')->default(0);
            $table->smallInteger('caromball')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_game_types');
    }
}

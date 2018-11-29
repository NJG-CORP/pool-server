<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGameTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_game_times', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->smallInteger('monday')->defaut(0);
            $table->smallInteger('tuesday')->defaut(0);
            $table->smallInteger('wednesday')->defaut(0);
            $table->smallInteger('thursday')->defaut(0);
            $table->smallInteger('friday')->defaut(0);
            $table->smallInteger('saturday')->defaut(0);
            $table->smallInteger('sunday')->defaut(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_game_times');
    }
}

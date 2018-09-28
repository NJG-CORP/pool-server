<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email')->nullable();
            $table->string('password');
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('name');
            $table->string('surname')->nullable();
            $table->unsignedTinyInteger('gender')->default(0);
            $table->unsignedInteger('location_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->time('game_time_from')->nullable();
            $table->time('game_time_to')->nullable();
            $table->string('api_token');
            $table->boolean('status');
            $table->rememberToken();
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}

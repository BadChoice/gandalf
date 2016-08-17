<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use BadChoice\Mojito\Models\Unit;

class CreateGandalf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_logins', function(Blueprint $table){
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('active')->default(1);
            $table->string('device');
            $table->tinyInteger('device_type')->unsigned();
            $table->string('platform');
            $table->string('browser');
            $table->string('location');
            $table->string('ip');
            $table->string('hash');

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
        Schema::dropIfExists('device_logins');
    }
}

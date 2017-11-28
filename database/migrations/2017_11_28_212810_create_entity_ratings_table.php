<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntityRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_ratings', function (Blueprint $table) {
            $table->integer('entity_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('entity_id')->references('id')->on('users');
            $table->foreign('user_id')->references('id')->on('entity');
            $table->primary(['entity_id', 'user_id']);
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
        Schema::dropIfExists('entity_ratings');
    }
}

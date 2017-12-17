<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Entity extends Migration
{

    public function up()
    {
        Schema::create('entity', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('title', 100);
            $table->string('description', 360);
            $table->string('thumbnail', 100);
            $table->string('url');
            $table->boolean('isEdited')->default(false);
            $table->boolean('isDeleted')->default(false);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('entity');
    }
}

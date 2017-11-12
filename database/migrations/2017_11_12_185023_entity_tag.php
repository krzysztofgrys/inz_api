<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EntityTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entity_tag', function (Blueprint $table) {
            $table->integer('tag_id')->unsigned();
            $table->integer('entity_id')->unsigned();
            $table->foreign('tag_id')->references('id')->on('tags');
            $table->foreign('entity_id')->references('id')->on('entity');
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
        //
    }
}

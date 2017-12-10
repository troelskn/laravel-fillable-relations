<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateThreeLevelFill extends Migration
{
    public function up()
    {
        Schema::create('threelevelfill_tools', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('threelevelfill_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('tool_id')->unsigned();
            $table->foreign('tool_id')->references('id')->on('threelevelfill_tools');
            $table->timestamps();
        });
        Schema::create('threelevelfill_choices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('field_id')->unsigned();
            $table->foreign('field_id')->references('id')->on('threelevelfill_fields');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('threelevelfill_tools');
        Schema::drop('threelevelfill_fields');
        Schema::drop('threelevelfill_choices');
    }
}
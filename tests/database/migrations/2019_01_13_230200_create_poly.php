<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePoly extends Migration
{
    public function up()
    {
        Schema::create('poly_forums', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
        });
        Schema::create('poly_title_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('text');
            $table->integer('translatable_id')->unsigned();
            $table->foreign('translatable_id')->references('id')->on('poly_forums');
            $table->string('translatable_type');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('poly_forums');
        Schema::drop('poly_title_translations');
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIamfaiz extends Migration
{
    public function up()
    {
        Schema::create('iamfaiz_tests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('preparation');
        });
        Schema::create('iamfaiz_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('options');
            $table->integer('test_id')->unsigned();
            $table->foreign('test_id')->references('id')->on('iamfaiz_tests');
        });
    }

    public function down()
    {
        Schema::drop('iamfaiz_tests');
        Schema::drop('iamfaiz_questions');
    }
}
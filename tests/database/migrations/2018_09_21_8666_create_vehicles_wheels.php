<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVehiclesWheels extends Migration
{
    protected $table = 'vehicles_wheels';

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vehicle_id')->unsigned();
            $table->integer('wheel_id')->unsigned();

            // pivot fields
            $table->string('colour')->nullable();
            $table->boolean('gold_plated')->nullable();
            $table->boolean('silver_plated')->nullable();
        });

    }

    public function down()
    {
        Schema::drop($this->table);
    }
}
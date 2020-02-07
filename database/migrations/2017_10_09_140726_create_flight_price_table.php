<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlightPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flight_price', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('price',8,2);
            $table->integer('flight_id')->unsigned();
            $table->integer('stationA_id')->unsigned();
            $table->integer('stationB_id')->unsigned();
            $table->foreign('flight_id')->references('id')->on('flights')->onDelete('cascade');
            $table->foreign('stationA_id')->references('id')->on('stations')->onDelete('cascade');
            $table->foreign('stationB_id')->references('id')->on('stations')->onDelete('cascade');
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
        Schema::dropIfExists('flight_price');
    }
}

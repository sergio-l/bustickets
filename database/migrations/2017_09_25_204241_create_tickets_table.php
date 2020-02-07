<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('price')->default(0);
            $table->integer('place')->nullable();
            $table->integer('client_id')->unsigned();
            $table->string('number');
            $table->enum('status', ['Оплачено','Забронировано']);
            $table->string('token')->nullable();
            $table->integer('flight_id')->unsigned()->nullable();
            $table->integer('stationA_id')->unsigned()->nullable();
            $table->integer('stationB_id')->unsigned()->nullable();
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('flight_id')->references('id')->on('flights')->onDelete('set null');
            $table->foreign('stationA_id')->references('id')->on('stations')->onDelete('set null');
            $table->foreign('stationB_id')->references('id')->on('stations')->onDelete('set null');
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
        Schema::dropIfExists('tickets');
    }
}

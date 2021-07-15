<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeatherStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weather_stations', function (Blueprint $table) {
            $table->id();
            $table->dateTime('time');
            $table->integer('temperature');
            $table->string('temperature_unit', 5);
            $table->integer('humidity');
            $table->integer('rain');
            $table->string('rain_unit', 5);
            $table->integer('wind');
            $table->string('wind_unit', 5);
            $table->integer('light')->nullable();
            $table->string('battery_level', 10);
            $table->enum('type',['US', 'EU']);
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
        Schema::dropIfExists('weather_stations');
    }
}

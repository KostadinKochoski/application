<?php

namespace App\Http\Controllers;

use App\Models\WeatherStation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WeatherStationController extends Controller
{
    /**
     * Display some information based on time and station type
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $weather_station = new WeatherStation();

        if ( $request->has( 'station' ) ) {
            $station_type = $request->get( 'station' );

            $weather_station = $weather_station->__call( $station_type, [] );
        }

        if ( $request->has( 'time' ) ) {
            $time = $request->get( 'time' );

            $weather_station = $weather_station->whereTime( $time );
        }

        $data = $weather_station->first();

        //todo::create a separate transformer for returning formatted data
        return response([
            'time'        => $data->time,
            'temperature' => $data->temperature . '°' . $data->temperature_unit,
            'humidity'    => $data->humidity . '%',
            'wind'        => $data->wind . ' ' . $data->wind_unit,
        ], 200);
    }

    /**
     * Display some information based on time and station type
     *
     * @param  Request  $request
     * @return Response
     */
    public function averageInfo(Request $request): Response
    {
        if ( $request->has( 'date' ) ) {
            $date = $request->get( 'date' );
        }

        $data = WeatherStation::getAverageInfo( $date );

        //todo::create a separate transformer for returning formatted data
        return response( $data, 200 );
    }

}

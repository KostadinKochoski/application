<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Flysystem\Exception;

/**
 * @property Carbon $time
 * @property integer $temperature
 * @property string $temperature_unit
 * @property integer $humidity
 * @property integer $rain
 * @property string $rain_unit
 * @property integer $wind
 * @property string $wind_unit
 * @property null|integer $light
 * @property string $battery_level
 * @property string $type
 */
class WeatherStation extends Model
{
    protected $fillable = [ 'time', 'temperature', 'temperature_unit', 'humidity', 'rain', 'rain_unit', 'wind', 'wind_unit', 'light', 'battery_level', 'type' ];

    protected $quarded = [ 'id' ];


    public function processFile( $file, $type )
    {
        Log::info( 'processing file data' );

        $type === 'json' ? $this->processJsonData( $file ) : $this->processCsvData( $file );
    }

    /**
     * This method checks if the file is previously processed, i.e. inserted in DB
     * @param $file
     * @param $type
     * @return bool
     */
    public function checkFile( $file, $type ): bool
    {
        $date = explode( '.', explode( '/', $file )[1] )[0];

        return WeatherStation::query()->whereRaw( 'date(created_at) = ?', [ Carbon::createFromDate( $date ) ] )->whereRaw( 'type = ?', [ $type ] )->count() ?? false;
    }

    private function processJsonData( $file )
    {
        Log::info('Reading data from json file');

        $data_array = json_decode( Storage::get( $file ), true );

        foreach ( $data_array as $data ) {
            //todo::add units and time depending on which unit system is used by the station
            $data['temperature_unit'] = 'F';
            $data['rain_unit']        = 'in/h';
            $data['wind_unit']        = 'mph';
            $data['light']            = null;
            $data['type']             = 'US';
            $data['time']             = Carbon::createFromTimestamp( $data['time'] );

            WeatherStation::create( $data );

            Log::info( 'New record created! [json]' );
        }

    }

    private function processCsvData( $file )
    {
        Log::info('Reading data from csv file');

        try {
            $reader = Reader::createFromPath( storage_path( 'app/' . $file ), 'r' );
            $reader->setDelimiter( ';' );
            $reader->setHeaderOffset( 0 );

            $records = Statement::create()->process( $reader );
            $records->getHeader();
        } catch ( Exception $e ) {
            Log::error( $e->getMessage() );
        }

        foreach ( $records as $record ) {
            //todo::add units and time depending on which unit system is used by the station

            $timestamp = explode( ',', $record['time'] );
            $date = str_replace( ':', '-', $timestamp[0] );

            $record['temperature_unit'] = 'C';
            $record['rain_unit']        = 'mm/h';
            $record['wind_unit']        = 'km/h';
            $record['type']             = 'EU';
            $record['time']             = Carbon::createFromTimestamp( strtotime( $date . $timestamp[1] ) );
            $record['battery_level']    = $record['battery level'];

            WeatherStation::create( $record );

            Log::info( 'New record created! [csv]' );
        }

    }

}

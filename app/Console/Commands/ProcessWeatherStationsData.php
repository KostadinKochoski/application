<?php

namespace App\Console\Commands;

use App\Models\WeatherStation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProcessWeatherStationsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weatherStations:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command process received files from all weather stations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info( 'Reading files from storage' );
        $done = 0;
        $files = Storage::files( 'WeatherStationsBucket' );

        foreach ( $files as $file ) {
            $type = pathinfo( storage_path( $file ), PATHINFO_EXTENSION );
            $weatherStation = new WeatherStation();

            Log::info( 'checking if the file has been processed' );

            if ( $weatherStation->checkFile( $file, $type ) ) {
                Log::info( 'this data has already been processed!' );
                continue;
            } else {
                $weatherStation->processFile( $file, $type );
            }

            $done++;

        }

        $this->info( 'Total processed files ' . $done . ' from ' . count( $files ) );
    }
}

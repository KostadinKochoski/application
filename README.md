<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About this application

steps to install the application

* clone the project
* run `composer install`
* copy .env.example file into new .env file and add configurations for database
* run migrations and seeders
* run the command `php artisan storage link`

There are two test files with data in storage/app/WeatherStationsBucket directory.
To process this two files, you should run the command `php artisan weatherStations:process`,
after finishing this step, the data should be inserted in DB  
Also some logs can be found in storage/logs/laravel.log


There are two api end points:
* `/weather-data` returns Information about temperature, humidity and wind from one of the stations (eu/us), for given date and time. Expects few parameters:
    * `station` -> it can be 'eu' or 'us'
    * `time` -> should be unix timestamp 
    * `api_tocken` ->  can be found in DB for the test user 
* `/average-weather-data` returns averaged information about temperature, humidity and wind from both stations, for given date. Expects few parameters:
    * `date` -> the date for we want to retrieve information, should be in format `dd-mm-yyyy`
    * `api_tocken` ->  can be found in DB for the test user 












<?php

namespace onethirtyone\GoogleCalendar;

use Illuminate\Support\ServiceProvider;
use onethirtyone\GoogleCalendar\classes\Calendar;

class GoogleCalendarServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/google-calendar.php' => config_path('google-calendar.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations'),
        ], 'migrations');

        $this->mergeConfigFrom(
            __DIR__ . '/config/google-calendar.php', 'google-calendar'
        );

        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        $this->app->bind('Client', function ($app) {
            return new Client();
        });

        $this->app->bind('Calendar', function ($app) {
            return new Calendar();
        });

    }

    public function register()
    {

    }
}
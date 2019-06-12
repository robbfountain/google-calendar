<?php

namespace onethirtyone\GoogleCalendar;

use Illuminate\Support\ServiceProvider;

class GoogleCalendarServiceProvider extends ServiceProvider {

    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/google-calendar.php' => config_path('google-calendar.php')
        ], 'config');

        $this->publishes([
            __DIR__.'/database/migrations' => database_path('migrations')
        ],'migrations');

        $this->mergeConfigFrom(
            __DIR__.'/config/google-calendar.php', 'google-calendar'
        );

        $this->loadRoutesFrom(__DIR__.'/routes.php');

    }

    public function register()
    {
        
    }
}
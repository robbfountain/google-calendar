<?php

namespace OneThirtyOne\GoogleCalendar;

use Illuminate\Support\ServiceProvider;

/**
 * Class GoogleCalendarServiceProvider
 * @package OneThirtyOne\GoogleCalendar
 */
class GoogleCalendarServiceProvider extends ServiceProvider
{
    /**
     *
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/google-calendar.php' => config_path('google-calendar.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/database/migrations' => database_path('migrations'),
        ], 'migrations');

        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'GoogleCalendar');
    }

    /**
     *
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/google-calendar.php', 'google-calendar'
        );
    }
}

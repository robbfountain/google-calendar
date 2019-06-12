<?php


Route::prefix('calendar')
    ->namespace('onethirtyone\GoogleCalendar\controllers')
    ->group(function () {
        Route::get('oauth2callback', 'CalendarOauthController@callback')
            ->name('calendar.oauth.callback');
    });


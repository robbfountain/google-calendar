<?php


Route::prefix('calendar')
    ->group(['namespace' => 'onethirtyone\GoogleCalendar\controllers'], function () {
        Route::get('oauth2callback', 'CalendarOauthController@callback')
            ->name('calendar.oauth.callback');
    });


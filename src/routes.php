<?php


Route::group([
    'namespace' => 'onethirtyone\GoogleCalendar\app\Http\Controllers',
    'prefix' => 'calendar',
], function () {
    Route::get('oauth2callback', 'CalendarOauthController@callback')
        ->name('calendar.oauth.callback');
});


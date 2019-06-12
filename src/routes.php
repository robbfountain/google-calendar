<?php


Route::group([
    'namespace' => 'onethirtyone\GoogleCalendar\app\Http\Controllers',
    'prefix' => 'calendar',
    'middleware' => 'web',
], function () {
    Route::get('oauth2callback', 'CalendarOauthController@callback')
        ->name('calendar.oauth.callback');
});


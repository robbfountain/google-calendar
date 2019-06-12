<?php


Route::group([
    'namespace' => 'onethirtyone\GoogleCalendar\controllers',
    'prefix' => 'calendar',
], function () {
    Route::get('oauth2callback', 'CalendarOauthController@callback')
        ->name('calendar.oauth.callback');
});


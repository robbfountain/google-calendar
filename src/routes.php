<?php

Route::group([
    'namespace' => 'OneThirtyOne\GoogleCalendar\App\Http\Controllers',
    'prefix' => '/google/calendar',
    'middleware' => ['web',\OneThirtyOne\GoogleCalendar\App\Http\Middleware\CalendarAuth::class],
], function () {

    Route::get('/', 'CalendarOauthController@index')
        ->name('calendar.index');

    Route::get('oauth2callback', 'CalendarOauthController@callback')
        ->name('calendar.oauth.callback');

    Route::get('unregister', 'CalendarOauthController@unRegister')
        ->name('calendar.oauth.unregister');

    Route::get('register', 'CalendarWebhookController@register')
        ->name('calendar.webhook.register');

    route::get('stop','CalendarWebhookController@unRegister')
        ->name('calendar.webhook.unregister');

});

Route::group([
    'namespace' => 'OneThirtyOne\GoogleCalendar\App\Http\Controllers',
    'prefix' => '/google/calendar',
    'middleware' => 'web',
], function () {

    Route::post('webhook', 'CalendarWebhookController@handle')
        ->name('calendar.webhook');

});

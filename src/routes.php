<?php


Route::group([
    'namespace' => 'onethirtyone\GoogleCalendar\app\Http\Controllers',
    'prefix' => '/google/client',
    'middleware' => 'web',
], function () {
    Route::get('oauth2callback', 'CalendarOauthController@callback')
        ->name('calendar.oauth.callback');

    Route::post('webhook', 'CalendarWebhookController@handle')
        ->name('calendar.webhook');

    Route::get('register','CalendarWebhookController@register')
        ->name('calendar.webhook.register');
});


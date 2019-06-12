<?php


Route::prefix('calendar')->group(function() {
    Route::get('oauth2callback','CalendarOauth2Controller@callback')
        ->name('calendar.oauth.callback');
});


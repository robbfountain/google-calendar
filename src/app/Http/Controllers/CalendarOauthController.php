<?php

namespace onethirtyone\GoogleCalendar\app\Http\Controllers;

use Illuminate\Http\Request;
use onethirtyone\GoogleCalendar\Client;
use onethirtyone\GoogleCalendar\app\GoogleCalendar;

class CalendarOauthController
{

    public function callback(Request $request, Client $calendar)
    {
        if ($request->has('code')) {
            GoogleCalendar::create($calendar->getAccessTokenFromAuthCode($request->code));
        }

        return 'updated';

    }

}
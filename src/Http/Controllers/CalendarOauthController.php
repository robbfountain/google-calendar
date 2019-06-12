<?php

namespace onethirtyone\GoogleCalendar\Controllers;

use Illuminate\Http\Request;
use onethirtyone\GoogleCalendar\Calendar;

class CalendarOauthController
{

    public function callback(Request $request, Calendar $calendar)
    {
        if($request->has('code'))
        {
            $calendar->getAccessTokenFromAuthCode($request->code);
        }

    }

}
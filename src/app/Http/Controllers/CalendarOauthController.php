<?php

namespace onethirtyone\GoogleCalendar\app\Http\Controllers;

use Illuminate\Http\Request;
use onethirtyone\GoogleCalendar\Client;

class CalendarOauthController
{

    public function callback(Request $request, Client $client)
    {
        if ($request->has('code')) {
            $client->createClientFromAuthCode($request->code);
        }

        return 'updated';

    }

}
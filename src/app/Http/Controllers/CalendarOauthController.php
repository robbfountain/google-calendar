<?php

namespace onethirtyone\GoogleCalendar\app\Http\Controllers;

use Illuminate\Http\Request;
use onethirtyone\GoogleCalendar\Client;

class CalendarOauthController
{

    public function callback(Request $request, Client $client)
    {
        if ($client->hasExistingToken()) {
            return redirect()
                ->to(route('calendar.index'))
                ->with(['message' => 'Token already exists']);
        }

        if ($request->has('error')) {
            throw new \Exception('Invalid Google Authentication Attempt');
        }

        if ($request->has('code')) {
            $client->createClientFromAuthCode($request->code);
            return redirect()->to(route('calendar.index'));
        }

        throw new \Exception('Invalid Attempt');

    }

    public function index()
    {
        return view('GoogleCalendar::calendar-inddex');
    }
}
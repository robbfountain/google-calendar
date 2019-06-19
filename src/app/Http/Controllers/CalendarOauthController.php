<?php

namespace onethirtyone\GoogleCalendar\app\Http\Controllers;

use Illuminate\Http\Request;
use onethirtyone\GoogleCalendar\Client;
use onethirtyone\GoogleCalendar\app\GoogleClient;

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
            return redirect()
                ->to(route('calendar.index'))
                ->withErrors('Invalid Google Authentication Attempt');
        }

        if ($request->has('code')) {
            $client->createClientFromAuthCode($request->code);
            return redirect()->to(route('calendar.index'))->with(['message' => 'Calendar linked successfully']);
        }

        return redirect()
            ->to(route('calendar.index'))
            ->withErrors('Invalid Google Authentication Attempt');
    }

    public function index()
    {
        return view('GoogleCalendar::calendar-index')
            ->with(['integrated' => GoogleClient::count()]);
    }

    public function unRegister()
    {
        GoogleClient::truncate();

        return redirect()->to(route('calendar.index'))
            ->with(['message' => 'Calendar integration removed']);
    }
}
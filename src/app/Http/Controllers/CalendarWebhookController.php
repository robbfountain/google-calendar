<?php

namespace onethirtyone\GoogleCalendar\app\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use onethirtyone\GoogleCalendar\Channel;
use onethirtyone\GoogleCalendar\GoogleCalendarFactory;

class CalendarWebhookController
{

    protected $channel;

    public function register()
    {
        $channel = new Channel();
        $channel->id = Str::uuid();
        $channel->type = 'web_hook';
        $channel->address = 'https://dev.131studios.com/google/calendar/webhook';
        $channel->save();

        return redirect()->to(route('calendar.index'))->with(['message' => 'Webhooks Enabled for Calendar']);
    }

    public function handle(Request $request)
    {
        //
    }

}
<?php

namespace onethirtyone\GoogleCalendar\app\Http\Controllers;

use Illuminate\Http\Request;
use onethirtyone\GoogleCalendar\Channel;

class CalendarWebhookController
{

    public function register(Channel $channel)
    {
        $channel->id = Str::uuid();
        $channel->type = 'web_hook';
        $channel->address = 'https://dev.131studios.com/google/client/webhook';
        $channel->save();

//        $channel =  new Google_Service_Calendar_Channel($client);
//        $channel->setId(Str::uuid());
//        $channel->setType('web_hook');
//        $channel->setAddress('https://staging.dapperhousebarbershop.com/google/client/webhook');
//
//        $watchEvent = $service->events->watch('primary', $channel);
    }

    public function handle(Request $request)
    {
        //
    }

}
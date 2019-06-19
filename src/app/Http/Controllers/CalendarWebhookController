<?php

namespace onethirtyone\GoogleCalendar\app\Http\Controllers;

use Google_Service_Calendar_Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use onethirtyone\GoogleCalendar\Client;

class CalendarWebhookController
{

    public function register(Client $client)
    {
        $channel =  new Google_Service_Calendar_Channel($client);
        $channel->setId(Str::uuid());
        $channel->setType('web_hook');
        $channel->setAddress('https://staging.dapperhousebarbershop.com/google/client/webhook');

        $watchEvent = $service->events->watch('primary', $channel);
    }

    public function handle(Request $request)
    {
        //
    }

}
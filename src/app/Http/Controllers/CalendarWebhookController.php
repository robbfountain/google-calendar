<?php

namespace onethirtyone\GoogleCalendar\app\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use onethirtyone\GoogleCalendar\Channel;
use onethirtyone\GoogleCalendar\GoogleCalendarFactory;

class CalendarWebhookController
{

    protected $channel;

    public function __construct(Channel $channel)
    {
        $this->channel = $channel;
    }

    public function register()
    {
        $this->channel->id = Str::uuid();
        $this->channel->type = 'web_hook';
        $this->channel->address = 'https://dev.131studios.com/google/client/webhook';
        $googleCalendar = GoogleCalendarFactory::getInstanceWithCalendarId('primary');
        return $googleCalendar->watch($this->channel);
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
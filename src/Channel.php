<?php


namespace onethirtyone\GoogleCalendar;


class Channel
{
    protected $client;

    protected $channel;

    public function __construct()
    {
        $this->client = GoogleCalendarFactory::getAuthenticatedClientInstance();
        $this->channel = new \Google_Service_Calendar_Channel($this->client);
    }

}
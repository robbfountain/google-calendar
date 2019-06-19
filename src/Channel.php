<?php


namespace onethirtyone\GoogleCalendar;


use Illuminate\Support\Str;

class Channel
{
    protected $client;

    protected $channel;

    protected $googleCalendar;

    public function __construct()
    {
        $this->client = GoogleCalendarFactory::getAuthenticatedClientInstance();
        $this->googleCalendar = GoogleCalendarFactory::getAuthenticatedClientInstance();
        $this->channel = new \Google_Service_Calendar_Channel($this->client);
    }

    public function getClient()
    {
        return $this->client;
    }

    public function __set($name, $value)
    {
        $method = Str::camel("set_{$name}");

        if (method_exists($this->channel, $method)) {
            $this->channel->$method($value);
        }
    }

    public function save()
    {
        return $this->googleCalendar->events->watch('primary', $this->channel);
    }

}
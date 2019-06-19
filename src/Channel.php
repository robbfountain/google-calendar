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
        $this->channel = new \Google_Service_Calendar_Channel($this->client);
    }

    public function getChannel()
    {
        return $this->channel;
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
        $googleCalendar = GoogleCalendarFactory::getInstanceWithCalendarId('primary');
        $response = $googleCalendar->watch($this->channel);
        Client::updateClientWithChannel($response);

        return $this;
    }
}
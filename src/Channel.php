<?php


namespace onethirtyone\GoogleCalendar;


use Illuminate\Support\Str;
use onethirtyone\GoogleCalendar\app\GoogleClient;

/**
 * Class Channel
 * @package onethirtyone\GoogleCalendar
 */
class Channel
{
    /**
     * @var \Google_Client
     */
    protected $client;

    /**
     * @var \Google_Service_Calendar_Channel
     */
    protected $channel;

    /**
     * @var
     */
    protected $googleCalendar;

    /**
     * Channel constructor.
     * @throws \Google_Exception
     */
    public function __construct()
    {
        $this->client = GoogleCalendarFactory::getAuthenticatedClientInstance();
        $this->channel = new \Google_Service_Calendar_Channel($this->client);
    }

    /**
     * @return \Google_Service_Calendar_Channel
     */
    public function getChannel()
    {
        return $this->channel;
    }

    public function getCurrent()
    {
        $client = GoogleClient::firstOrFail();
        $this->channel->setResourceId($client->channel_resource_id);
        $this->channel->setId($client->channel_unique_id);

        return $this;
    }

    /**
     * @return \Google_Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $method = Str::camel("set_{$name}");

        if (method_exists($this->channel, $method)) {
            $this->channel->$method($value);
        }
    }

    /**
     * @return $this
     * @throws \Google_Exception
     */
    public function save()
    {
        $googleCalendar = GoogleCalendarFactory::getInstanceWithCalendarId('primary');
        $response = $googleCalendar->watch($this->channel);
        Client::updateClientWithChannel($response);

        return $this;
    }

    public function stop()
    {
        $googleCalendar = GoogleCalendarFactory::getInstanceWithCalendarId('primary');
        $response = $googleCalendar->stop($this->channel);

        return $this;
    }


}
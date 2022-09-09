<?php


namespace OneThirtyOne\GoogleCalendar;


use Illuminate\Support\Str;
use OneThirtyOne\GoogleCalendar\app\Models\GoogleClient;

/**
 * Class Channel
 * @package OneThirtyOne\GoogleCalendar
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

    /**
     * @return $this
     */
    public function getCurrentChannel()
    {
        $client = GoogleClient::firstOrFail();
        $this->resourceId = $client->channel_resource_id;
        $this->id = $client->channel_unique_id;

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
     * @param null $calendarId
     *
     * @return $this
     * @throws \Google_Exception
     */
    public function save($calendarId = null)
    {
        $googleCalendar = static::getGoogleCalendarInstance($calendarId);
        $channel = $googleCalendar->watch($this->channel);

        Client::updateClientWithChannel([
            'channel_unique_id' => $channel->getId(),
            'channel_resource_id' => $channel->getResourceId(),
            'channel_expires_at' => $channel->getExpiration(),
            'channel_resource_url' => $channel->getResourceUri(),
        ]);

        return $this;
    }

    /**
     * @param null $calendarId
     *
     * @return $this
     * @throws \Google_Exception
     */
    public function stop($calendarId = null)
    {
        $googleCalendar = static::getGoogleCalendarInstance($calendarId);
        $googleCalendar->stop($this->channel);

        Client::updateClientWithChannel([
            'channel_unique_id' => null,
            'channel_resource_id' => null,
            'channel_expires_at' => null,
            'channel_resource_url' => null,
        ]);

        return $this;
    }

    /**
     * @param $calendarId
     *
     * @return Calendar
     * @throws \Google_Exception
     */
    protected static function getGoogleCalendarInstance($calendarId)
    {
        $calendarId = $calendarId ?? 'primary';

        return GoogleCalendarFactory::getInstanceWithCalendarId($calendarId);
    }
}

<?php

namespace onethirtyone\GoogleCalendar;

use Carbon\Carbon;
use Google_Service_Calendar;
use onethirtyone\GoogleCalendar\app\GoogleClient;

/**
 * Class Calendar
 * @package onethirtyone\GoogleCalendar
 */
class Calendar
{
    /**
     * @var Google_Service_Calendar
     */
    protected $googleCalendar;

    /**
     * @var string
     */
    protected $calendarId;

    /**
     * Calendar constructor.
     *
     * @param Google_Service_Calendar $googleCalendar
     * @param string                  $calendarId
     */
    public function __construct(Google_Service_Calendar $googleCalendar, string $calendarId)
    {
        $this->googleCalendar = $googleCalendar;
        $this->calendarId = $calendarId;
    }

    /**
     * @param       $event
     * @param array $optParams
     *
     * @return \Google_Service_Calendar_Event
     */
    public function insertEvent($event, $optParams = [])
    {
        if ($event instanceof Event) {
            $event = $event->googleEvent;
        }

        return $this->googleCalendar->events->insert($this->calendarId, $event, $optParams);
    }

    /**
     * @param $event
     *
     * @return \Google_Service_Calendar_Event
     */
    public function updateEvent($event)
    {
        if ($event instanceof Event) {
            $event = $event->googleEvent;
        }

        return $this->googleCalendar->events->update($this->calendarId, $event->id, $event);
    }

    /**
     * @param $channel
     *
     * @return \Google_Service_Calendar_Channel
     */
    public function watch($channel)
    {
        return $this->googleCalendar->events->watch($this->calendarId, $channel);
    }

    /**
     * @param $channel
     *
     * @return \expectedClass|\Google_Http_Request
     */
    public function stop($channel)
    {
        return $this->googleCalendar->channels->stop($channel);
    }

    /**
     * @return mixed
     */
    public static function hasExistingWebhooks()
    {
        return GoogleClient::where('channel_unique_id','!=',null)->exists();
    }

    /**
     * @param $eventId
     *
     * @return \Google_Service_Calendar_Event
     */
    public function findEvent($eventId)
    {
        return $this->googleCalendar->events->get($this->calendarId, $eventId);
    }

    /**
     * @return string
     */
    public function getCalendarId()
    {
        return $this->calendarId;
    }

    /**
     * @param $start
     * @param $end
     * @param $parameters
     *
     * @return \Google_Service_Calendar_Events
     */
    public function listEvents($start, $end, $parameters)
    {
        $defaultParameters = [
            'singleEvents' => true,
            'orderBy' => 'startTime',
        ];

        if (is_null($start)) {
            $start = Carbon::now()->startOfDay();
        }

        $defaultParameters['timeMin'] = $start->format(\DateTime::RFC3339);

        if (is_null($end)) {
            $end = Carbon::now()->endOfYear();
        }

        $defaultParameters['timeMax'] = $end->format(\DateTime::RFC3339);

        $defaultParameters = array_merge($defaultParameters, $parameters);

        return $this->googleCalendar->events->listEvents($this->calendarId, $defaultParameters);
    }

    /**
     * @param $parameters
     *
     * @return \Google_Service_Calendar_Events
     */
    public function listAllEvents($parameters)
    {
        $defaultParameters = [
            'singleEvents' => true,
        ];

        return $this->googleCalendar->events->listEvents($this->calendarId, array_merge($defaultParameters, $parameters));
    }



    /**
     * @param $eventId
     */
    public function deleteEvent($eventId)
    {
        $this->googleCalendar->events->delete($this->calendarId, $eventId);
    }
}
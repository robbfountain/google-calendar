<?php

namespace onethirtyone\GoogleCalendar;

use Carbon\Carbon;
use Google_Service_Calendar;

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
     * @return string
     */
    public function getCalendarId()
    {
        return $this->calendarId;
    }

    public function listEvents($start, $end, $parameters)
    {
        $defaultParameters = [
            'singleEvents' => true,
            'orderBy' => 'startTime',
        ];

        if(is_null($start))
        {
            $start = Carbon::now()->startOfDay();
        }

        $defaultParameters['timeMin'] = $start->format(\DateTime::RFC3339);


        if(is_null($end))
        {
            $end = Carbon::now()->endOfYear();
        }

        $defaultParameters['timeMax'] = $end->format(\DateTime::RFC3339);

        $defaultParameters = array_merge($defaultParameters, $parameters);

        return $this->googleCalendar->events->listEvents($this->calendarId, $defaultParameters)->getItems();
    }
}
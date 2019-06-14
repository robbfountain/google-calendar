<?php

namespace onethirtyone\GoogleCalendar;

use Google_Service_Calendar;

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

    public function insertEvent($event, $optParams = [])
    {
        if ($event instanceof Event) {
            $event = $event->googleEvent;
        }

        return $this->googleCalendar->events->insert($this->calendarId, $event, $optParams);
    }

    public function getCalendarId()
    {
        return $this->calendarId;
    }
}
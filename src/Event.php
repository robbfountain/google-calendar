<?php

namespace onethirtyone\GoogleCalendar;

use DateTime;
use Carbon\Carbon;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;

class Event
{
    /**
     * @var \Google_Service_Calendar_Event
     */
    public $googleEvent;

    /**
     * @var string
     */
    public $calendarId;

    /**
     * @var array
     */
    public $attendees;


    public function __construct()
    {
        $this->googleEvent = new Google_Service_Calendar_Event;
        $this->attendees = [];
    }

    public function __set($name, $value)
    {
        $name = $this->getName($name);

        if (in_array($name, ['start.date', 'end.date', 'start.dateTime', 'end.dateTime'])) {
            $this->formatForDateTime($name, $value);
            return;
        }

        array_set($this->googleEvent, $name, $value);
    }

    protected function getName($name)
    {
        return [
                'name' => 'summary',
                'description' => 'description',
                'startDate' => 'start.date',
                'endDate' => 'end.date',
                'startDateTime' => 'start.dateTime',
                'endDateTime' => 'end.dateTime',
            ][$name] ?? $name;
    }

    public function formatForDateTime($name, Carbon $date)
    {
        $eventDateTime = new Google_Service_Calendar_EventDateTime;

        if (in_array($name, ['start.date', 'end.date'])) {
            $eventDateTime->setDate($date->format('Y-m-d'));
            $eventDateTime->setTimezone($date->getTimezone());
        }
        if (in_array($name, ['start.dateTime', 'end.dateTime'])) {
            $eventDateTime->setDateTime($date->format(DateTime::RFC3339));
            $eventDateTime->setTimezone($date->getTimezone());
        }
        if (starts_with($name, 'start')) {
            $this->googleEvent->setStart($eventDateTime);
        }
        if (starts_with($name, 'end')) {
            $this->googleEvent->setEnd($eventDateTime);
        }
    }

    public function save(string $method = null, $optParams = [])
    {
        // determine what we're doing
        $method = $method ?? 'insertEvent';

        // get an instance of the google calendar
        $googleCalendar = $this->getGoogleCalendarInstance($this->calendarId);

        // set the attendees
        $this->googleEvent->setAttendees($this->attendees);

        //create the event
        $googleEvent = $googleCalendar->$method($this, $optParams);

        // return the event
        return static::createFromGoogleCalendarEvent($googleEvent, $googleCalendar->getCalendarId());
    }

    public static function createFromGoogleCalendarEvent(Google_Service_Calendar_Event $googleEvent, $calendarId)
    {
        $event = new static;

        $event->googleEvent = $googleEvent;
        $event->calendarId = $calendarId;

        return $event;
    }

    public function getGoogleCalendarInstance($calendarId)
    {
        $calendarId = $calendarId ?? 'primary';

        return GoogleCalendarFactory::getInstanceWithCalendarId($calendarId);
    }

    public function get()
    {
        return $this->googleEvent;
    }

}
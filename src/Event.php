<?php

namespace onethirtyone\GoogleCalendar;

use DateTime;
use Carbon\Carbon;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;

/**
 * Class Event
 * @package onethirtyone\GoogleCalendar
 */
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


    /**
     * Event constructor.
     */
    public function __construct()
    {
        $this->googleEvent = new Google_Service_Calendar_Event;
        $this->attendees = [];
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $name = $this->getName($name);

        if (in_array($name, ['start.date', 'end.date', 'start.dateTime', 'end.dateTime'])) {
            $this->formatForDateTime($name, $value);
            return;
        }

        array_set($this->googleEvent, $name, $value);
    }

    /**
     * @param $name
     *
     * @return mixed
     */
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

    /**
     * @param        $name
     * @param Carbon $date
     */
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

    /**
     * @param string|null $method
     * @param array       $optParams
     *
     * @return Event
     */
    public function save(string $method = null, $optParams = [])
    {
        // determine what we're doing
        $method = $method ?? 'insertEvent';

        // get an instance of the google calendar
        $googleCalendar = static::getGoogleCalendarInstance($this->calendarId);

        // set the attendees
        $this->googleEvent->setAttendees($this->attendees);

        //create the event
        $googleEvent = $googleCalendar->$method($this, $optParams);

        // return the event
        return static::createFromGoogleCalendarEvent($googleEvent, $googleCalendar->getCalendarId());
    }

    /**
     * @param Google_Service_Calendar_Event $googleEvent
     * @param                               $calendarId
     *
     * @return Event
     */
    public static function createFromGoogleCalendarEvent(Google_Service_Calendar_Event $googleEvent, $calendarId)
    {
        $event = new static;

        $event->googleEvent = $googleEvent;
        $event->calendarId = $calendarId;

        return $event;
    }

    /**
     * @param $calendarId
     *
     * @return Calendar
     */
    public static function getGoogleCalendarInstance($calendarId)
    {
        $calendarId = $calendarId ?? 'primary';

        return GoogleCalendarFactory::getInstanceWithCalendarId($calendarId);
    }

    /**
     * @return Google_Service_Calendar_Event
     */
    public function get()
    {
        return $this->googleEvent;
    }

    /**
     * @param Carbon|null $start
     * @param Carbon|null $end
     * @param array       $parameters
     * @param string|null $calendarId
     */
    public static function list(Carbon $start = null, Carbon $end = null, array $parameters = [], string $calendarId = null )
    {
        $googleCalendar = static::getGoogleCalendarInstance($calendarId);

        $calendarEvents = $googleCalendar->listEvents($start, $end, $parameters);

        return collect($calendarEvents)->map(function (Google_Service_Calendar_Event $event) use ($calendarId) {
            return static::createFromGoogleCalendarEvent($event, $calendarId);
        });
    }

}
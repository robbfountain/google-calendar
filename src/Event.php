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
     * @param Carbon|null $start
     * @param Carbon|null $end
     * @param array       $parameters
     * @param string|null $calendarId
     *
     * @return \Illuminate\Support\Collection
     */
    public static function list(Carbon $start = null,
        Carbon $end = null,
        array $parameters = [],
        string $calendarId = null)
    {
        $googleCalendar = static::getGoogleCalendarInstance($calendarId);
        $calendarEventsCollection = collect();
        $pageToken = null;

        do {
            $calendarEvents = $googleCalendar->listEvents($start, $end, array_merge($parameters, static::getPageToken()));
            $calendarEventsCollection->push($calendarEvents->getItems());
            $pageToken = $calendarEvents->getNextPageToken();
        } while($pageToken != null);

        // TODO: Store Sync Token
        dd($calendarEventsCollection->count());

        return $calendarEventsCollection->map(function ( $event) use (
            $calendarId
        ) {
            return static::createFromGoogleCalendarEvent($event, $calendarId);
        });
    }

    public static function getPageToken($token)
    {
        return $token ? ['pageToken' => $token] : [];
    }
    /**
     * @param $calendarId
     *
     * @return Calendar
     */
    protected static function getGoogleCalendarInstance($calendarId)
    {
        $calendarId = $calendarId ?? 'primary';

        return GoogleCalendarFactory::getInstanceWithCalendarId($calendarId);
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
     * @param array $attributes
     * @param string|null $calendarId
     * @param array $optParams
     *
     * @return Event
     */
    public static function create(array $attributes, string $calendarId = null, $optParams = [])
    {
        $event = new static;

        $event->calendarId = static::getGoogleCalendarInstance($calendarId)->getCalendarId();

        foreach ($attributes as $name => $value) {
            $event->$name = $value;
        }

        return $event->save('insertEvent', $optParams);

    }

    /**
     * @param string|null $method
     * @param array       $optParams
     *
     * @return Event
     */
    public function save(string $method = null, $optParams = [])
    {
        $method = $method ?? ($this->exists() ? 'updateEvent' : 'insertEvent');

        $googleCalendar = $this->getGoogleCalendarInstance($this->calendarId);

        $this->googleEvent->setAttendees($this->attendees);

        $googleEvent = $googleCalendar->$method($this, $optParams);

        return static::createFromGoogleCalendarEvent($googleEvent, $googleCalendar->getCalendarId());
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return $this->id != '';
    }

    public static function find($eventId, string $calendarId = mnull)
    {
        $googleCalendar = static::getGoogleCalendarInstance($calendarId);

        $googleEvent = $googleCalendar->findEvent($eventId);

        return static::createFromGoogleCalendarEvent($googleEvent, $calendarId);
    }

    /**
     * @param string|null $eventId
     */
    public function delete(string $eventId = null)
    {
        $this->getGoogleCalendarInstance($this->calendarId)->deleteEvent($eventId ?? $this->id);
    }

    /**
     * @param $name
     *
     * @return bool|DateTime|mixed
     */
    public function __get($name)
    {
        $name = $this->getName($name);

        $value = array_get($this->googleEvent, $name);

        if (in_array($name, ['start.date', 'end.date']) && $value) {
            $value = Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
        }

        if (in_array($name, ['start.dateTime', 'end.dateTime']) && $value) {
            $value = Carbon::createFromFormat(DateTime::RFC3339, $value);
        }

        return $value;
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
     * @param array $attendees
     */
    public function addAttendee(array $attendees)
    {
        $this->attendees[] = $attendees;
    }
}
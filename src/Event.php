<?php

namespace onethirtyone\GoogleCalendar;

use DateTime;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Carbon\Carbon;

class Event
{
    /**
     * @var \Google_Service_Calendar_Event
     */
    public $googleEvent;

    public function __construct()
    {
        $this->googleEvent = new Google_Service_Calendar_Event;
    }

    public function __set($name, $value)
    {
        $name = $this->getName($name);

        if(in_array($name, ['start.date','end.date','start.dateTime','end.dateTime']))
        {
            $this->formatTime($name,$value);
            return;
        }

        array_set($this->googleEvent, $name, $value);
    }

    public function formatTime($name, Carbon $date)
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

    protected function getName($name)
    {
        return [
                'name'          => 'summary',
                'description'   => 'description',
                'startDate'     => 'start.date',
                'endDate'       => 'end.date',
                'startDateTime' => 'start.dateTime',
                'endDateTime'   => 'end.dateTime',
            ][$name] ?? $name;
    }

    public function get()
    {
        return $this->googleEvent;
    }

}
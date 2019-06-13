<?php

namespace onethirtyone\GoogleCalendar;

use Google_Service_Calendar;
use Google_Service_Calendar_Event;

class Calendar
{
    protected $client;
    protected $calendar;
    protected $calendarId = 'primary';
    protected $optParams = [
        'maxResults' => 10,
        'orderBy'      => 'startTime',
        'singleEvents' => true,
    ];

    /**
     * Calendar constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->calendar =  new Google_Service_Calendar($this->client->fetch());
    }

    public function calendar($id)
    {
        $this->calendarId = $id;

        return $this;
    }

    public function listEvents($options = [])
    {
        $results = $this->calendar->events->listEvents($this->calendarId, array_merge($this->optParams, $options));
        return $results->getItems();
    }

    public function createEvent($details, $calendarId = null)
    {
        $calendarId = $calendarId ?? $this->calendarId;
        $event = new Google_Service_Calendar_Event($details);

        $event = $this->calendar->events->insert($calendarId, $event);
        return 'Event created: ' .  $event->htmlLink;

    }
}